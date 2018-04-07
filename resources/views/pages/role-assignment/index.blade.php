@extends('layouts.default')

@section('title')
    @lang('title.roles')
@endsection

@section('content')

    <h1>@lang('title.roles')</h1>
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
                        @if(!empty($roleAssignment->assigned_by))
                            {{ $roleAssignment->assigned_by->user->username }}
                        @else
                            @lang('title.unknown')
                        @endif
                    </td>
                    <td>
                        {{ $roleAssignment->created_at->diffForHumans() }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        {{-- TODO: Create better "no items found" display --}}
        <p>@lang('phrase.no-items-found', ['item' => __('title.roles')])</p>
    @endif
@endsection