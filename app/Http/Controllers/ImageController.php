<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Zeropingheroes\Lanager\Requests\StoreImageRequest;

class ImageController extends Controller
{
    /**
     * Permitted extensions
     */
    const permittedExtensions = ['gif', 'jpg', 'jpeg', 'png', 'bmp'];

    /**
     * Relative image storage location
     */
    const imagePath = 'public/images';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all files in image path (and its subfolders)
        $files = collect(Storage::allFiles($this::imagePath));

        // Only show image files
        $images = $files->filter(function ($value) {
                return in_array(strtolower(File::extension($value)), $this::permittedExtensions);
            }
        );

        // Add fields to collection
        $images = $images->map(function ($item) {
                return [
                    'url' => Storage::url($item),
                    'filename' => File::basename($item),
                    'folder' => str_replace_first('/','',str_after(File::dirname($item), $this::imagePath)),
                ];
            }
        );

        // Sort collection by folder
        $images = $images->sortBy('folder');

        return View::make('pages.images.index')
            ->with('images', $images);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $folders = collect($this->imageDirectories());

        $folders = $folders->map(function ($item) {
            return str_replace_first('/','',str_after($item, $this::imagePath));
        });

        return View::make('pages.images.create')
            ->with('folders', $folders);
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
        $input = [
            'images' => $httpRequest->images,
            'folder' => $httpRequest->input('folder'),
        ];

        $request = new StoreImageRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }

        // Store each file
        foreach($httpRequest->images as $image) {
            $fileNameWithExtension = $image->getClientOriginalName();
            $extension = $image->getClientOriginalExtension();

            $fileName = str_replace($extension,'', $fileNameWithExtension);

            $newFileName = str_slug($fileName).'.'.strtolower($extension);
            $folder = $this::imagePath.'/'.$httpRequest->folder;

            $image->storeAs($folder, $newFileName);
        }

        return redirect()
            ->route('images.index')
            ->withSuccess(__('phrase.images-successfully-uploaded'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }

    /**
     * @return mixed
     */
    private function imageDirectories()
    {
        $folders = Storage::allDirectories($this::imagePath);
        return $folders;
    }
}
