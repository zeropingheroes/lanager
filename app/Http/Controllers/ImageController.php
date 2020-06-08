<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use File;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Storage;
use Str;
use View;
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
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
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
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest)
    {
        $this->authorize('images.create');

        $input = [
            'images' => $httpRequest->images,
        ];

        $request = new StoreImageRequest($input);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());
            return redirect()->back()->withInput();
        }

        // Store each file
        foreach ($httpRequest->images as $image) {
            $fileNameWithExtension = $image->getClientOriginalName();
            $extension = $image->getClientOriginalExtension();

            $fileName = str_replace($extension, '', $fileNameWithExtension);

            $newFileName = Str::slug($fileName) . '.' . strtolower($extension);

            $image->storeAs($this::directory, $newFileName);
        }

        Session::flash('success',__('phrase.images-successfully-uploaded'));

        return redirect()->route('images.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $filename
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
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
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $httpRequest, string $filename)
    {
        $this->authorize('images.update');

        $originalFilePath = $this::directory . '/' . $filename;
        $originalFileExtension = File::extension($originalFilePath);
        $newFilenameWithoutExtension = Str::before($httpRequest->input('filename'), '.' . $originalFileExtension);
        $newFilePath = $this::directory . '/' . $newFilenameWithoutExtension . '.' . $originalFileExtension;

        $input = [
            'original_file_path' => $originalFilePath,
            'new_file_path' => $newFilePath,
            'new_filename_without_extension' => $newFilenameWithoutExtension,
        ];

        $request = new UpdateImageRequest($input);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());
            return redirect()->back()->withInput();
        }

        Storage::move($originalFilePath, $newFilePath);

        return redirect()
            ->route('images.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $filename
     * @return RedirectResponse
     * @throws AuthorizationException
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

        Session::flash(
            'success',
            __('phrase.item-name-deleted', ['item' => __('title.image'), 'name' => $filename])
           );

        return redirect()->route('images.index');
    }
}
