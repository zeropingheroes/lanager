@extends('layouts.default')

@section('title')
    @lang('title.logs')
@endsection

@section('content')

    <h1>@lang('title.logs')</h1>
    {{ Breadcrumbs::render('logs.index') }}

    @include('components.alerts.all')

    @include('pages.logs.partials.filter')

    <script>
        $(function () {
            $("#checkAll").click(function () {
                $('input:checkbox').not(this).prop('checked', this.checked);
            });
        });
    </script>

    <form method="POST" action="{{ route('logs.patch') }}">
        @method('PATCH')
        {{ csrf_field() }}
        <table class="table">
            <thead>
            <tr>
                <th>@lang('title.time')</th>
                <th>@lang('title.level')</th>
                <th>@lang('title.message')</th>
                <th>@lang('title.user')</th>
                <th>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="checkAll">
                        <label class="custom-control-label" for="checkAll"></label>
                    </div>
                </th>
            </tr>
            </thead>
            @if(count($logs))
                <tbody>
                @foreach( $logs as $log )
                    <tr class="{{ $log->read ? '' : 'table-active font-weight-bold' }}">
                        <td>
                            @include('components.time-relative', ['datetime' => $log->created_at])
                        </td>
                        <td>
                            @include('pages.logs.partials.level', ['level' => $log->level_name])
                        </td>
                        <td>
                            <a href="{{ route('logs.show', $log->id) }}">
                                {{ str_limit($log->message, 96) }}
                            </a>
                        </td>
                        <td>
                            @if($log->user)
                                @include('pages.users.partials.avatar-username', ['user' => $log->user])
                            @endif
                        </td>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="checkbox-{{ $log->id }}"
                                       name="logs[{{$log->id}}][read]" value="1">
                                <label class="custom-control-label" for="checkbox-{{ $log->id }}"></label>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            @else
                <tbody>
                <tr>
                    <td colspan="5">@lang('phrase.no-items-found', ['item' => strtolower(__('title.logs'))])</td>
                </tr>
                </tbody>
            @endif
        </table>
        <div class="row">
            <div class="col-md">
                {{ $logs->links() }}
            </div>
            <div class="col-md">
                <button type="submit" class="btn btn-primary float-right">@lang('phrase.mark-as-read')</button>
            </div>
        </div>
    </form>

@endsection