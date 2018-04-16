@extends('layouts.default')

@section('title')
    @lang('title.users')
@endsection

@section('content')

    <h1>@lang('title.users')</h1>
    @if(count($users))
        <table class="table table-striped">
            <tbody>
            @foreach( $users as $user )
                <tr>
                    <td>
                        @include('pages.user.partials.avatar-username', ['user' => $user])
                    </td>
                    <td>
                        {{ $user->state->status() }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>@lang('phrase.no-items-found', ['item' => 'users'])</p>
    @endif
@endsection