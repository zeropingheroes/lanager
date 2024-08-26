{{csrf_field()}}
<div class="form-group mb-3">
    <label for="name">@lang('title.name')</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="@lang('title.name')"
           value="{{ old('name', $achievement->name) }}">
</div>
<div class="form-group mb-3">
    <label for="image">@lang('title.achievement-image')</label>
        <div class="input-group">
            <input type="file" class="form-control" id="image" name="image">
        </div>
    <small>@lang('phrase.achievement-image-help')</small>
</div>
<div class="form-group mb-3">
    <label for="description">@lang('title.description')</label>
    <textarea class="form-control" id="description" name="description" rows="10"
              placeholder="@lang('phrase.markdown-help')"
              aria-describedby="descriptionHelp">{{ old('description', $achievement->description) }}</textarea>
    <small id="descriptionHelp" class="form-text text-muted">
        <a href="@lang('phrase.markdown-formatting-help-link-url')"
           target="_blank">@lang('phrase.markdown-formatting-help-link')</a>
        <br>
        <a href="{{ route('images.index') }}" target="_blank">@lang('title.upload-images')</a>
    </small>
</div>
<button type="submit" class="btn btn-primary">@lang('title.submit')</button>

<script>
    window.addEventListener('load', function() {
        // Show selected files in file input label
        $("input[type=file]").change(function () {
            var files = $(this).prop("files");
            var fieldVal = $.map(files, function(val) { return ' ' + val.name; });
            if (fieldVal != undefined || fieldVal != "") {
                $(this).next(".custom-file-label").text(fieldVal);
            }
        });
    })
</script>
