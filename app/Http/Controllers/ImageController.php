<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use File;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Zeropingheroes\Lanager\Requests\StoreImageRequest;
use Zeropingheroes\Lanager\Requests\UpdateImageRequest;

class ImageController extends Controller
{
    /**
     * Permitted extensions.
     */
    public const array PERMITTED_EXTENSIONS = ['gif', 'jpg', 'jpeg', 'png', 'bmp', 'webp'];

    /**
     * Uploaded image storage location.
     */
    public const string DIRECTORY = 'images';

    /**
     * Display a listing of the resource.
     *
     * @throws AuthorizationException
     */
    public function index(): ViewContract
    {
        $this->authorize('images.view');

        // Get all files in image path
        $files = collect(Storage::disk('public')->files(self::DIRECTORY));

        // Only show image files
        $images = $files->filter(
            fn ($value) => in_array(strtolower(File::extension($value)), self::PERMITTED_EXTENSIONS, true)
        );

        // Add fields to collection
        $images = $images->map(
            fn ($item) => [
                'url' => Storage::url($item),
                'filename' => File::basename($item),
            ]
        );

        return View::make('pages.images.index')
            ->with('images', $images);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest): RedirectResponse
    {
        $this->authorize('images.create');

        $input = [
            'images' => $httpRequest->images,
        ];

        $storeImageRequest = new StoreImageRequest($input);

        if ($storeImageRequest->invalid()) {
            Session::flash('error', $storeImageRequest->errors());

            return redirect()->back()->withInput();
        }

        // Store each file
        foreach ($httpRequest->images as $image) {
            $fileNameWithExtension = $image->getClientOriginalName();
            $extension = $image->getClientOriginalExtension();

            $fileName = str_replace($extension, '', $fileNameWithExtension);

            $newFileName = Str::slug($fileName).'.'.strtolower((string) $extension);

            $image->storeAs(self::DIRECTORY, $newFileName, 'public');
        }

        Session::flash('success', trans('phrase.images-successfully-uploaded'));

        return redirect()->route('images.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @throws AuthorizationException
     */
    public function edit(string $filename): ViewContract
    {
        $this->authorize('images.update');

        $filePath = self::DIRECTORY.'/'.$filename;
        if (! Storage::disk('public')->exists($filePath)) {
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
     * @throws AuthorizationException
     */
    public function update(Request $httpRequest, string $filename): RedirectResponse
    {
        $this->authorize('images.update');

        $originalFilePath = self::DIRECTORY.'/'.$filename;
        $originalFileExtension = File::extension($originalFilePath);
        $newFilenameWithoutExtension = Str::before($httpRequest->input('filename'), '.'.$originalFileExtension);
        $newFilePath = self::DIRECTORY.'/'.$newFilenameWithoutExtension.'.'.$originalFileExtension;

        //        $originalFilePath = Storage::disk('public')->path(self::DIRECTORY.'/'.$filename);
        //        $originalFileExtension = File::extension($originalFilePath);
        //        $newFilenameWithoutExtension = Str::before($httpRequest->input('filename'), '.'.$originalFileExtension);
        //        $newFilePath = self::DIRECTORY.'/'.$newFilenameWithoutExtension.'.'.$originalFileExtension;

        $input = [
            'original_file_path' => $originalFilePath,
            'new_file_path' => $newFilePath,
            'new_filename_without_extension' => $newFilenameWithoutExtension,
        ];

        $updateImageRequest = new UpdateImageRequest($input);

        if ($updateImageRequest->invalid()) {
            Session::flash('error', $updateImageRequest->errors());

            return redirect()->back()->withInput();
        }

        Storage::disk('public')->move($originalFilePath, $newFilePath);

        return redirect()
            ->route('images.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws AuthorizationException
     */
    public function destroy(string $filename): RedirectResponse
    {
        $this->authorize('images.delete');

        $file = self::DIRECTORY.'/'.$filename;
        if (! Storage::disk('public')->exists($file)) {
            abort(404);
        }

        Storage::disk('public')->delete($file);

        Session::flash(
            'success',
            trans('phrase.item-name-deleted', ['item' => trans('title.image'), 'name' => $filename])
        );

        return redirect()->route('images.index');
    }
}
