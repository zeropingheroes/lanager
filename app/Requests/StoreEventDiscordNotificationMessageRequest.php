<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Requests;

use Illuminate\Support\Facades\Storage;
use Zeropingheroes\Lanager\Http\Controllers\ImageController;
use Zeropingheroes\Lanager\Services\DiscordWebhookService;

class StoreEventDiscordNotificationMessageRequest extends Request
{
    use LaravelValidation;

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function valid(): bool
    {
        $this->validationRules = [
            'message' => ['nullable', 'string', 'max:2000'],
            'image_paths' => [
                'nullable',
                'array',
                'max:'.DiscordWebhookService::MAX_IMAGES,
            ],
            'image_paths.*' => ['string'],
        ];

        if (! $this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        $totalSize = 0;
        $imageDir = realpath(Storage::disk('public')->path(ImageController::DIRECTORY));

        foreach ($this->input['image_paths'] ?? [] as $path) {
            if (! Storage::disk('public')->exists($path)) {
                $this->addError(trans('phrase.discord-image-not-in-library'));

                return $this->setValid(false);
            }

            $resolvedPath = realpath(Storage::disk('public')->path($path));

            if (
                $resolvedPath === false
                || $imageDir === false
                || ! str_starts_with($resolvedPath, $imageDir.DIRECTORY_SEPARATOR)
            ) {
                $this->addError(trans('phrase.discord-image-not-in-library'));

                return $this->setValid(false);
            }

            $extension = strtolower(pathinfo((string) $path, PATHINFO_EXTENSION));
            if (! in_array($extension, DiscordWebhookService::PERMITTED_IMAGE_EXTENSIONS, true)) {
                $this->addError(
                    trans(
                        'phrase.discord-image-unsupported-extension',
                        ['types' => implode(', ', DiscordWebhookService::PERMITTED_IMAGE_EXTENSIONS)])
                );

                return $this->setValid(false);
            }

            $fileSize = Storage::disk('public')->size($path);

            if ($fileSize > DiscordWebhookService::MAX_FILE_BYTES) {
                $this->addError(trans('phrase.discord-image-single-file-too-large', ['max' => intdiv(DiscordWebhookService::MAX_FILE_BYTES, 1_048_576).'MB']));

                return $this->setValid(false);
            }

            $totalSize += $fileSize;
        }

        if ($totalSize > DiscordWebhookService::MAX_TOTAL_BYTES) {
            $this->addError(trans('phrase.discord-images-total-size-exceeded', ['max' => intdiv(DiscordWebhookService::MAX_TOTAL_BYTES, 1_048_576).'MB']));

            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
