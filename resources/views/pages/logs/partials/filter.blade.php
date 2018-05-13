@php
    $levels = [
        '100' => ['label' => 'Debug',     'class' => 'text-info'],
        '200' => ['label' => 'Info',      'class' => 'text-info'],
        '250' => ['label' => 'Notice',    'class' => 'text-warning'],
        '300' => ['label' => 'Warning',   'class' => 'text-warning'],
        '400' => ['label' => 'Error',     'class' => 'text-danger'],
        '500' => ['label' => 'Critical',  'class' => 'text-danger'],
        '600' => ['label' => 'Alert',     'class' => 'text-danger'],
        '700' => ['label' => 'Emergency', 'class' => 'text-danger'],
    ];
@endphp
<form class="form-inline mb-1" method="get" action="{{ route('logs.index') }}">
    <label class="my-1 mr-2" for="minimum-level">@lang('phrase.minimum-level')</label>
    <select class="custom-select my-1 mr-sm-2 col-2" id="minimum-level" name="minimum-level">
        @foreach ($levels as $key => $attributes)
            <option value="{{ $key }}"
                    @if(request('minimum-level', 250) == $key)
                        selected="selected"
                    @endif
                    class="{{ $attributes['class'] }}"
            >{{ $attributes['label'] }}</option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-primary my-1">Submit</button>
</form>