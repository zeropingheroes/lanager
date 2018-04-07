@extends('layouts.default')

@section('title')
    {{ $title }}
@endsection

@section('content')

    <div class="profile-header">
        <div class="profile-avatar">
            Avatar
        </div>
        <h1>
            {{ $user->username }}
        </h1>
    </div>
    <div class="profile-content">
        <p>Profile content</p>
    </div>

@endsection
