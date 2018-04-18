@extends('layouts.default')

@section('title')
    @lang('title.roles')
@endsection

@section('content')

    <h1>@lang('title.roles')</h1>
    @include('components.alerts')
    @if(count($roleAssignments))
        <table class="table table-striped">
            <thead>
            <tr>
                <th>@lang('title.user')</th>
                <th>@lang('title.role')</th>
                <th colspan="2">@lang('title.assigned-by')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($roleAssignments as $roleAssignment)
                <tr>
                    <td>
                        @include('pages.user.partials.avatar-username', ['user' => $roleAssignment->user])
                    </td>
                    <td>
                        {{ $roleAssignment->role->name }}
                    </td>
                    <td>
                        @if($roleAssignment->assigner)
                            @include('pages.user.partials.username', ['user' => $roleAssignment->assigner])
                        @else
                            @lang('title.unknown')
                        @endif
                    </td>
                    <td>
                        @include('components.time-relative', ['datetime' => $roleAssignment->created_at])
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        {{-- TODO: Create better "no items found" display --}}
        <p>@lang('phrase.no-items-found', ['item' => __('title.roles')])</p>
    @endif
    @can('create', Zeropingheroes\Lanager\RoleAssignment::class)
        <h5>@lang('title.assign-a-role')</h5>
        {{ Form::model(Zeropingheroes\Lanager\RoleAssignment::class, ['route' => 'role-assignments.store']) }}
        <div class="form-inline">
            <div class="form-group">
                {{ Form::label('user_id', 'User', ['class' => 'custom-control-label mr-sm-2']) }}
                {{ Form::select('user_id', $users, null, ['class' => 'custom-select custom-control-inline form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('role_id', 'Role', ['class' => 'custom-control-label mr-sm-2']) }}
                {{ Form::select('role_id', $roles,  null, ['class' => 'custom-select custom-control-inline form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::submit(__('title.assign-role'), ['class' => 'btn btn-primary']) }}
            </div>
        </div>
        {{ Form::close() }}
    @endcan
@endsection