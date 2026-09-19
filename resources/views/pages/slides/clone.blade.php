@extends('layouts.default')

@section('title')
    @lang('title.clone-item', ['item' => __('title.slide')])
@endsection

@section('content-header')
    <h1>@lang('title.clone-item', ['item' => __('title.slide')])</h1>
    {{ Breadcrumbs::render('lans.slides.clone.create', $lan, $slide) }}
@endsection

@section('content')
    @include('components.form.create', ['route' => route('lans.slides.clone.store', ['lan' => $lan, 'slide' => $slide])])

    <div class="row mb-3">
        @include('components.form.label', ['for' => 'lan_id', 'text' => __('title.clone-to-lan'), 'required' => true])
        <div class="col-sm-10">
            @include('components.form.select', [
                'name' => 'lan_id',
                'item' => $slide,
                'items' => $lans,
                'labelField' => 'name',
            ])
        </div>
    </div>

    <script type="application/json" id="lans-data">{!! $lans->map(fn ($destinationLan) => [
        'id' => $destinationLan->id,
        'start' => $destinationLan->start->toIso8601String(),
        'end' => $destinationLan->end->toIso8601String(),
    ])->toJson() !!}</script>

    @include('pages.slides.partials.form', ['outOfRangeWarning' => true])
    @include('components.form.close')

    @vite('resources/js/pages/lan-item-clone-form.js')
@endsection
