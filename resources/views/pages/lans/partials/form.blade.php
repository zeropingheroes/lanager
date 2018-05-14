<div class="form-group">
    <label for="name">@lang('title.name')</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="@lang('title.name')"
           value="{{ old('name', $lan->name) }}">
</div>
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="start">@lang('title.start')</label>
        <input type="text" class="form-control" id="start" name="start" placeholder="YYYY-MM-DD HH:MM:SS"
               value="{{ old('start', $lan->start) }}">
    </div>
    <div class="form-group col-md-6">
        <label for="end">@lang('title.end')</label>
        <input type="text" class="form-control" id="end" name="end" placeholder="YYYY-MM-DD HH:MM:SS"
               value="{{ old('end', $lan->end) }}">
    </div>
</div>
<div class="form-group">
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="published" name="published"
               value="1" {{ old('published', $lan->published) ? 'checked' : null}}>
        <label class="custom-control-label" for="published">@lang('title.published')</label>
    </div>
</div>
<button type="submit" class="btn btn-primary">@lang('title.submit')</button>