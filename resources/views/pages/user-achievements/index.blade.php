@extends('layouts.default')

@section('title')
    @lang('title.achievements')
@endsection

@section('content-header')
    <h1>@lang('title.achievements')</h1>
    {{ Breadcrumbs::render('lans.user-achievements.index', $lan) }}
@endsection

@section('content')
    <table class="table table-striped">
        <tbody>
        @foreach($userAchievements as $userAchievement)
            @can('view', $userAchievement)
                <tr>
                    <td>
                        @include('pages.users.partials.avatar-username', ['user' => $userAchievement->user])
                    </td>
                    <td>
                        @if($userAchievement->achievement->image_filename)
                            <img src="/storage/images/achievements/{{ $userAchievement->achievement->image_filename }}" height="32px" width=32px">
                        @endif
                        <span title="{{ $userAchievement->achievement->description }}">{{ $userAchievement->achievement->name }}</span>
                    </td>
                    <td>
                        @include('components.time-relative', ['datetime' => $userAchievement->created_at])
                    </td>
                    <td>
                        @can('delete', $userAchievement)
                            @component('components.actions-dropdown')
                                <form action="{{ route('lans.user-achievements.destroy', ['lan' => $userAchievement->lan, 'userAchievement' => $userAchievement]) }}"
                                      method="POST" class="confirm-deletion">
                                    {{ method_field('DELETE') }}
                                    {{ csrf_field() }}
                                    <a class="dropdown-item" href="#"
                                       onclick="$(this).closest('form').submit();">@lang('title.delete')</a>
                                </form>
                            @endcomponent
                        @endcan
                    </td>
                </tr>
            @endcan
        @endforeach
        </tbody>
    </table>

    @can('create', Zeropingheroes\Lanager\RoleAssignment::class)
        <h5>@lang('title.award-an-achievement')</h5>
        @include('components.form.create', ['route' => route('lans.user-achievements.store', $lan)])
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
                <label for="achievement_id" class="custom-control-label mr-sm-2">@lang('title.achievement')</label>
                @include('components.form.select', [
                    'name' => 'achievement_id',
                    'items' => $achievements,
                    'labelField' => 'name',
                    'classes' => 'custom-select custom-control-inline form-control'
                ])
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">@lang('title.award')</button>
            </div>
        </div>
        @include('components.form.close')
    @endcan
@endsection