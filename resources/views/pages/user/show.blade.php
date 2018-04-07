@extends('layouts.default')

@section('title')
    {{ $user->username }}
@endsection

@section('content')

    <div class="profile-header">
        <div class="profile-avatar">
            @include('pages.user.partials.avatar',['size' => 'large', 'status' => 'offline'])
        </div>
        <h1>
            {{ $user->username }}
        </h1>
    </div>
    <div class="profile-content">
        <p>Profile content</p>
    </div>

@endsection
