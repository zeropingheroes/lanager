<div class="form-group">
    <label for="ip_range">@lang('title.ip-range')</label>
    <input type="text" class="form-control" id="ip_range" name="ip_range" placeholder="@lang('title.ip-range')"
           value="{{ old('ip_range', $whitelistedIpRange->ip_range) }}">
</div>
<div class="form-group">
    <label for="description">@lang('title.description')</label>
    <input type="text" class="form-control" id="description" name="description" placeholder="@lang('title.description')"
           value="{{ old('description', $whitelistedIpRange->description) }}">
</div>
<button type="submit" class="btn btn-primary">@lang('title.submit')</button>
