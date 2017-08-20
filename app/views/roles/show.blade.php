@extends('layouts.default')
@section('content')
    @include('layouts.default.title')
    @include('layouts.default.alerts')

    @include('user-roles.partials.list', ['userRoles' => $role->userRoles ])

    @include('buttons.edit', ['resource' => 'roles', 'item' => $role])
    @include('buttons.destroy', ['resource' => 'roles', 'item' => $role])

@endsection				
