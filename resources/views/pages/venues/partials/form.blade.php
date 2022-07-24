<div class="form-group">
    <label for="name">@lang('title.name')</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="@lang('title.name')"
           value="{{ old('name', $venue->name) }}">
</div>
<div class="form-group">
    <label for="street_address">@lang('title.street-address')</label>
    <input type="text" class="form-control" id="street_address" name="street_address" placeholder="@lang('title.street-address')"
           value="{{ old('street_address', $venue->street_address) }}">
</div>
<button type="submit" class="btn btn-primary">@lang('title.submit')</button>
