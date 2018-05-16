<div class="form-group">
    <label for="name">@lang('title.name')</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="@lang('title.name')"
           value="{{ old('name', $eventType->name) }}">
</div>
<div class="form-group">
    <label for="colour">@lang('title.colour')</label>
    <input type="text" class="form-control" id="colour" name="colour" placeholder="@lang('title.colour')"
           value="{{ old('colour', $eventType->colour) }}">
</div>
<button type="submit" class="btn btn-primary">@lang('title.submit')</button>