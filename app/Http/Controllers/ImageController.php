<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

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
                return in_array(File::extension($value), $this::permittedExtensions);
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
        $folders = $this->imageDirectories();

        return View::make('pages.images.create')
            ->with('folders', $folders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
