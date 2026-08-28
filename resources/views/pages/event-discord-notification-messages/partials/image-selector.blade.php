<div class="row mb-3">
    @include('components.form.label', ['text' => __('title.image-attachments'), 'class' => 'col-sm-2 col-form-label pt-0'])
    <div class="col-sm-10">
        <div id="discord-image-selector-app">
            <discord-notification-message-image-selector
                :available-images='@json($availableImages)'
                :selected-images='@json($selectedImages)'
                :images-url='"{{ route('images.index') }}"'
                :max-images="{{ $maxImages }}"
                :max-file-bytes="{{ $maxFileBytes }}"
                :max-total-bytes="{{ $maxTotalBytes }}"
            ></discord-notification-message-image-selector>
        </div>
    </div>
</div>
