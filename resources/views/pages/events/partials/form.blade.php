<div class="form-group">
    <label for="name">@lang('title.name')</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="@lang('title.name')"
           value="{{ old('name', $event->name) }}">
</div>
<button type="submit" class="btn btn-primary">@lang('title.submit')</button>