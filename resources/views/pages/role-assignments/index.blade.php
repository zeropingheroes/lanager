@extends('layouts.default')

@section('title')
    @lang('title.roles')
@endsection

@section('content')

    <h1>@lang('title.roles')</h1>
    @include('components.alerts.all')
    @if(count($roleAssignments))
        <table class="table table-striped">
            <thead>
            <tr>
                <th>@lang('title.user')</th>
                <th>@lang('title.role')</th>
                <th colspan="2">@lang('title.assigned-by')</th>
                @can('delete', Zeropingheroes\Lanager\RoleAssignment::class)
                    <th><span class="oi oi-cog" title="Cog" aria-hidden="true"></span></th>
                @endcan
            </tr>
            </thead>
            <tbody>
            @foreach($roleAssignments as $roleAssignment)
                <tr>
                    <td>
                        @include('pages.users.partials.avatar-username', ['user' => $roleAssignment->user])
                    </td>
                    <td>
                        {{ $roleAssignment->role->name }}
                    </td>
                    <td>
                        @if($roleAssignment->assigner)
                            @include('pages.users.partials.username', ['user' => $roleAssignment->assigner])
                        @else
                            @lang('title.unknown')
                        @endif
                    </td>
                    <td>
                        @include('components.time-relative', ['datetime' => $roleAssignment->created_at])
                    </td>
                    <td>
                        @if(Auth::user() && Auth::user()->id != $roleAssignment->user->id)
                            @component('components.actions-dropdown')
                                @include('components.actions-dropdown.delete', ['item' => $roleAssignment])
                            @endcomponent
                        @endif
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
        @include('components.form.create', ['route' => route('role-assignments.store')])
        <div class="form-inline">
            <div class="form-group">
                <label for="user_id" class="custom-control-label mr-sm-2">@lang('title.user')</label>
                @include('components.form.select', [
                    'name' => 'user_id',
                    'items' => $users,
                    'labelField' => 'username',
                    'classes' => 'custom-select custom-control-inline form-control'
                ])
            </div>
            <div class="form-group">
                <label for="role_id" class="custom-control-label mr-sm-2">@lang('title.role')</label>
                @include('components.form.select', [
                    'name' => 'role_id',
                    'items' => $roles,
                    'labelField' => 'name',
                    'classes' => 'custom-select custom-control-inline form-control'
                ])
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">@lang('title.assign-role')</button>
            </div>
        </div>
        @include('components.form.close')
    @endcan
@endsection