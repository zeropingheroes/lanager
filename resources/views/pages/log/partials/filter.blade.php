@php
    $levels = [
        '700' => 'Emergency',
        '600' => 'Alert',
        '500' => 'Critical',
        '400' => 'Error',
        '300' => 'Warning',
        '250' => 'Notice',
        '200' => 'Info',
        '100' => 'Debug',
    ];
@endphp
<form class="form-inline mb-1" method="get" action="{{ route('logs.index') }}">
    <label class="my-1 mr-2" for="minimum-level">@lang('phrase.minimum-level')</label>
    {{  Form::select('minimum-level', $levels, old('minimum-level', 250), ['class' => 'custom-select my-1 mr-sm-2 col-2']) }}

    <button type="submit" class="btn btn-primary my-1">Submit</button>
</form>