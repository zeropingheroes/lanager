<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Services;

use Illuminate\Support\Facades\Storage;
use Zeropingheroes\Lanager\Http\Controllers\ImageController;

class LocalImageService
{
    /**
     * Return images in the "public" storage directory and their metadata, optionally filtered by extension.
     *
     * @param  array<int, string>  $extensions  Lowercase extensions to include. Empty means no filter.
     * @return array<int, array{path: string, filename: string, size: int, url: string}>
     */
    public function all(array $extensions = []): array
    {
        $paths = Storage::disk('public')->files(ImageController::DIRECTORY);
        $images = [];

        foreach ($paths as $path) {
            if ($extensions !== []) {
                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                if (! in_array($extension, $extensions, true)) {
                    continue;
                }
            }

            $images[] = [
                'path' => $path,
                'filename' => basename($path),
                'size' => Storage::disk('public')->size($path),
                'url' => Storage::disk('public')->url($path),
            ];
        }

        usort($images, fn (array $a, array $b) => strcmp($a['filename'], $b['filename']));

        return $images;
    }

    /**
     * Return image metadata for a list of public-disk paths.
     *
     * @param  array<int, string>  $paths
     * @return array<int, array{path: string, filename: string, size: int, url: string}>
     */
    public function fromPaths(array $paths): array
    {
        return array_map(fn (string $path) => [
            'path' => $path,
            'filename' => basename($path),
            'size' => Storage::disk('public')->exists($path)
                ? Storage::disk('public')->size($path)
                : 0,
            'url' => Storage::disk('public')->url($path),
        ], $paths);
    }
}
