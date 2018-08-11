<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Zeropingheroes\Lanager\Requests\StoreImageRequest;
use Zeropingheroes\Lanager\Requests\UpdateImageRequest;

class ImageController extends Controller
{
    /**
     * Permitted extensions
     */
    const permittedExtensions = ['gif', 'jpg', 'jpeg', 'png', 'bmp'];

    /**
     * Uploaded image storage location
     */
    const directory = 'public/images';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('images.view');

        // Get all files in image path
        $files = collect(Storage::files($this::directory));

        // Only show image files
        $images = $files->filter(
            function ($value) {
                return in_array(strtolower(File::extension($value)), $this::permittedExtensions);
            }
        );

        // Add fields to collection
        $images = $images->map(
            function ($item) {
                return [
                    'url' => Storage::url($item),
                    'filename' => File::basename($item),
                ];
            }
        );

        return View::make('pages.images.index')
            ->with('images', $images);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $httpRequest
     * @return \Illuminate\Http\Response
     * @internal param Request|StoreRoleAssignmentRequest $request
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $httpRequest)
    {
        $this->authorize('images.create');

        $input = [
            'images' => $httpRequest->images,
        ];

        $request = new StoreImageRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }

        // Store each file
        foreach ($httpRequest->images as $image) {
            $fileNameWithExtension = $image->getClientOriginalName();
            $extension = $image->getClientOriginalExtension();

            $fileName = str_replace($extension, '', $fileNameWithExtension);

            $newFileName = str_slug($fileName) . '.' . strtolower($extension);

            $image->storeAs($this::directory, $newFileName);
        }

        return redirect()
            ->route('images.index')
            ->withSuccess(__('phrase.images-successfully-uploaded'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $filename
     * @return \Illuminate\Http\Response
     * @internal param \Zeropingheroes\Lanager\Lan $lan
     */
    public function edit(string $filename)
    {
        $this->authorize('images.update');

        $filePath = $this::directory . '/' . $filename;
        if (!Storage::exists($filePath)) {
            abort(404);
        }

        $image = [
            'url' => Storage::url($filePath),
            'filename' => File::basename($filePath),
            'name' => File::name($filePath),
            'extension' => File::extension($filePath),
        ];

        return View::make('pages.images.edit')
            ->with('image', $image);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $httpRequest
     * @param string $filename
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function update(Request $httpRequest, string $filename)
    {
        $this->authorize('images.update');

        $originalFilePath = $this::directory . '/' . $filename;
        $originalFileExtension = File::extension($originalFilePath);
        $newFilenameWithoutExtension = str_before($httpRequest->input('filename'), '.' . $originalFileExtension);
        $newFilePath = $this::directory . '/' . $newFilenameWithoutExtension . '.' . $originalFileExtension;

        $input = [
            'original_file_path' => $originalFilePath,
            'new_file_path' => $newFilePath,
            'new_filename_without_extension' => $newFilenameWithoutExtension,
        ];

        $request = new UpdateImageRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }

        Storage::move($originalFilePath, $newFilePath);

        return redirect()
            ->route('images.index')
            ->withSuccess(__('phrase.image-filename-successfully-updated', ['filename' => $filename]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $filename)
    {
        $this->authorize('images.delete');

        // TODO: move to Request class
        $file = $this::directory . '/' . $filename;
        if (!Storage::exists($file)) {
            abort(404);
        }

        Storage::delete($file);

        return redirect()
            ->route('images.index')
            ->withSuccess(__('phrase.image-filename-successfully-deleted', ['filename' => $filename]));
    }
}
