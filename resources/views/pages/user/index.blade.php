@extends('layouts.default')

@section('title', $title)

@section('content')
    <h1>{{ $title }}</h1>
    @if(count($users))
        <table class="table table-striped">
            <tbody>
            @foreach( $users as $user )
                <tr>
                    <td>
                        <a href="{{ route('users.show', $user->id) }}">{{ $user->username }}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>@lang('phrase.no-items-found', ['item' => 'users'])</p>
    @endif
@endsection