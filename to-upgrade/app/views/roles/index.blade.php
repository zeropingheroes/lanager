@extends('layouts.default')
@section('content')
    @include('layouts.default.title')
    @include('layouts.default.alerts')

    @if (count($roles))
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Assigned To</th>
                @if ( Authority::can('manage', 'roles') )
                    <th class="text-center">{{ Icon::cog() }}</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach( $roles as $role )
                <tr>
                    <td>{{ link_to_route('roles.show', $role->name, $role->id) }}</td>
                    <td>
                        @include('plural', ['singular' => 'user', 'collection' => $role->users, 'hover' => $role->users->lists('username') ])
                    </td>
                    @if ( Authority::can('manage', 'roles') )
                        <td class="text-center">
                            @include(
                                'buttons.create',
                                [
                                    'resource' => 'user-roles',
                                    'size' => 'extraSmall',
                                    'icon' => 'user',
                                    'hover' => 'Assign this role to a user',
                                    'parameters' => ['role_id' => $role->id],
                                ])
                            @include('buttons.edit', ['resource' => 'roles', 'item' => $role, 'size' => 'extraSmall'])
                            @include('buttons.destroy', ['resource' => 'roles', 'item' => $role, 'size' => 'extraSmall'])
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>No roles found!</p>
    @endif

    @include('buttons.create', ['resource' => 'roles'])

@endsection
