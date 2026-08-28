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
                            <form action="{{ route( 'role-assignments.destroy', $roleAssignment->id) }}"
                                  method="POST"
                                  class="d-inline">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit"
                                        class="btn btn-sm btn-danger"
                                        onclick="confirmFormSubmit(event)"
                                        title="@lang('title.delete')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endcan
        @endforeach
        </tbody>
    </table>
    <p>
        @lang('phrase.admin-vs-superadmin').
    </p>

    @can('create', Zeropingheroes\Lanager\Models\RoleAssignment::class)
        <h5>@lang('title.assign-a-role')</h5>

        <form method="POST"
              action="{{ route('role-assignments.store') }}"
              accept-charset="UTF-8"
              class="d-flex flex-row align-items-center flex-wrap">
            {{ csrf_field() }}
            <label for="user_id" class="my-1 me-2">@lang('title.user')</label>
            @include('components.form.select', [
                'name' => 'user_id',
                'items' => $users,
                'labelField' => 'username',
                'classes' => 'form-select my-1 me-2 w-auto'
            ])
            <label for="role_id" class="my-1 me-2">@lang('title.role')</label>
            @include('components.form.select', [
                'name' => 'role_id',
                'items' => $roles,
                'labelField' => 'display_name',
                'classes' => 'form-select my-1 me-2 w-auto'
            ])

            <button type="submit" class="btn btn-primary my-1"><i class="fa-solid fa-user-shield"></i> @lang('title.assign-role')</button>
        </form>
    @endcan
@endsection
