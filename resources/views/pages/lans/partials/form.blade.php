@vite('resources/js/pages/lan-form.js')
@include('components.form.inputs.name', ['value' => $lan->name])
@include('components.form.inputs.start-end', ['start' => $lan->start, 'end' => $lan->end])
<div class="row mb-3">
    <label for="venue_id"
           class="col-sm-2 col-form-label"
    >
        @lang('title.venue')
    </label>
    <div class="col-sm-10">
        @include('components.form.select', [
            'name' => 'venue_id',
            'item' => $lan,
            'items' => $venues,
            'labelField' => 'name',
            'blank' => true,
            ])
    </div>
</div>
<div class="row mb-3">
    <label for="achievement_id"
           class="col-sm-2 col-form-label"
    >
        @lang('title.lan-achievement')
    </label>
    <div class="col-sm-10">
        @include('components.form.select',[
            'name' => 'achievement_id',
            'item' => $lan,
            'items' => $achievements,
            'labelField' => 'name',
            'blank' => true,
        ])
        <small id="achievement_id_help"
               class="form-text"
        >
            @lang('phrase.lan-achievement-help')
        </small>
    </div>
</div>
<div class="row mb-3">
    <label for="discord_webhook_url"
           class="col-sm-2 col-form-label"
    >
        @lang('title.discord-webhook-url')
    </label>
    <div class="col-sm-10">
        <input type="url"
               class="form-control"
               id="discord_webhook_url"
               name="discord_webhook_url"
               placeholder="https://discord.com/api/webhooks/..."
               value="{{ old('discord_webhook_url', $lan->discord_webhook_url) }}"
               aria-describedby="discord_webhook_url_help"
        >
        <small id="discord_webhook_url_help"
               class="form-text text-muted"
        >
            @lang('phrase.discord-webhook-url-help')
        </small>
    </div>
</div>
@include('components.form.inputs.published', ['value' => $lan->published])
@include('components.form.inputs.submit')
