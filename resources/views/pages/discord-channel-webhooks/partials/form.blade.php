@php $bothConfigured = count($availablePurposes) === 0; @endphp

@include('components.form.create', ['route' => route('lans.discord-channel-webhooks.store', ['lan' => $lan])])

<div class="row g-2 align-items-center">
    <div class="col-auto">
        <select id="purpose"
                name="purpose"
                class="form-select"
            {{ $bothConfigured ? 'disabled' : '' }}
        >
            @foreach($availablePurposes as $purpose)
                <option value="{{ $purpose }}" {{ old('purpose') === $purpose ? 'selected' : '' }}>
                    {{ trans('title.' . $purpose) }}
                </option>
            @endforeach
            @if($bothConfigured)
                <option disabled>@lang('title.live')</option>
                <option disabled>@lang('title.test')</option>
            @endif
        </select>
    </div>

    <div class="col">
        <input type="url"
               id="webhook_url"
               name="webhook_url"
               class="form-control"
               placeholder="https://discord.com/api/webhooks/..."
               value="{{ old('webhook_url') }}"
            {{ $bothConfigured ? 'disabled' : '' }}
        >
    </div>

    <div class="col-auto">
        <button type="submit" class="btn btn-primary" {{ $bothConfigured ? 'disabled' : '' }}>
            @lang('title.submit')
        </button>
    </div>
</div>

@if($bothConfigured)
    <p class="text-muted small mt-2">@lang('phrase.discord-channel-webhook-already-configured')</p>
@endif

@include('components.form.close')
