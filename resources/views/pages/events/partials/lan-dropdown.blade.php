<div class="form-group mb-3">
    <label for="lan_id">@lang('title.lan')</label>
    @include('components.form.select', ['name' => 'lan_id', 'item' => $event, 'items' => $lans, 'labelField' => 'name'])
</div>
