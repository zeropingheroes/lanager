@extends('layouts.default')

@section('title')
    @lang('title.role-assignments')
@endsection

@section('content-header')
    <h1>@lang('title.role-assignments')</h1>
    {{ Breadcrumbs::render('role-assignments.index') }}
@endsection

@section('content')
    <table class="table table-striped">
        <tbody>
        @foreach($roleAssignments as $roleAssignment)
            @can('view', $roleAssignment)
                <tr>
                    <td>
                        @include('pages.users.partials.avatar-username', ['user' => $roleAssignment->user])
                    </td>
                    <td>
                        {{ $roleAssignment->role->display_name }}
                    </td>
                    <td>
                        @if($roleAssignment->assigner)
                            @include('pages.users.partials.username', ['user' => $roleAssignment->assigner])
                        @else
                            @lang('title.unknown')
                        @endif
                        @lang('phrase.assigned')
                        @include('components.time-relative', ['datetime' => $roleAssignment->created_at])
                    </td>
                    <td>
                        @can('delete', $roleAssignment)
                            @component('components.actions-dropdown')
                                @include('components.actions-dropdown.delete', ['item' => $roleAssignment])
                            @endcomponent
                        @endcan
                    </td>
                </tr>
            @endcan
        @endforeach
        </tbody>
    </table>

    @can('create', Zeropingheroes\Lanager\Models\RoleAssignment::class)
        <h5>@lang('title.assign-a-role')</h5>
        @include('components.form.create', ['route' => route('role-assignments.store')])
        <div class="form-inline">
            <div class="form-group">
                <label for="user_id" class="mr-sm-2">@lang('title.user')</label>
                @include('components.form.select', [
                    'name' => 'user_id',
                    'items' => $users,
                    'labelField' => 'username',
                    'classes' => 'custom-select custom-control-inline form-control'
                ])
            </div>
            <div class="form-group">
                <label for="role_id" class="mr-sm-2">@lang('title.role')</label>
                @include('components.form.select', [
                    'name' => 'role_id',
                    'items' => $roles,
                    'labelField' => 'display_name',
                    'classes' => 'custom-select custom-control-inline form-control'
                ])
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">@lang('title.assign-role')</button>
            </div>
        @include('components.form.close')
    @endcan
@endsection
