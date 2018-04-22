<div class="mb-2">
    <div class="custom-control custom-checkbox custom-control-inline">
        <input class="custom-control-input" type="checkbox" id="emergency" value="emergency">
        <label class="custom-control-label" for="emergency">@include('pages.log.partials.level', ['level' => 'EMERGENCY'])</label>
    </div>
    <div class="custom-control custom-checkbox custom-control-inline">
        <input class="custom-control-input" type="checkbox" id="alert" value="alert">
        <label class="custom-control-label" for="alert">@include('pages.log.partials.level', ['level' => 'ALERT'])</label>
    </div>
    <div class="custom-control custom-checkbox custom-control-inline">
        <input class="custom-control-input" type="checkbox" id="critical" value="critical">
        <label class="custom-control-label" for="critical">@include('pages.log.partials.level', ['level' => 'CRITICAL'])</label>
    </div>
    <div class="custom-control custom-checkbox custom-control-inline">
        <input class="custom-control-input" type="checkbox" id="error" value="error">
        <label class="custom-control-label" for="error">@include('pages.log.partials.level', ['level' => 'ERROR'])</label>
    </div>
    <div class="custom-control custom-checkbox custom-control-inline">
        <input class="custom-control-input" type="checkbox" id="warning" value="warning">
        <label class="custom-control-label" for="warning">@include('pages.log.partials.level', ['level' => 'WARNING'])</label>
    </div>
    <div class="custom-control custom-checkbox custom-control-inline">
        <input class="custom-control-input" type="checkbox" id="notice" value="notice">
        <label class="custom-control-label" for="notice">@include('pages.log.partials.level', ['level' => 'NOTICE'])</label>
    </div>
    <div class="custom-control custom-checkbox custom-control-inline">
        <input class="custom-control-input" type="checkbox" id="info" value="info">
        <label class="custom-control-label" for="info">@include('pages.log.partials.level', ['level' => 'INFO'])</label>
    </div>
    <button class="btn btn-primary" type="submit">Filter</button>
</div>