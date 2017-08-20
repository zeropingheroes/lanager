@if (count($userAchievements))
    <table class="table">
        <thead>
        <tr>
            <th>User</th>
            <th>Achievement</th>
            <th colspan="2">Awarded At</th>
            @if ( Authority::can('manage', 'user-achievements') )
                <th class="text-center">{{ Icon::cog() }}</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach( $userAchievements as $userAchievement )
            <tr>
                <td>
                    @include('users.partials.avatar-username', ['user' => $userAchievement->user])
                </td>
                <td>
                    {{ link_to_route('achievements.show', $userAchievement->achievement->name, $userAchievement->achievement->id, ['title' => $userAchievement->achievement->description] ) }}
                </td>
                <td>
                    {{ $userAchievement->lan->name}}
                </td>
                <td>
                    {{ (new ExpressiveDate($userAchievement->created_at))->format('D g:ia') }}
                </td>
                @if ( Authority::can('manage', 'user-achievements'))
                    <td class="text-center">
                        @include('buttons.edit', ['resource' => 'user-achievements', 'item' => $userAchievement, 'size' => 'extraSmall'])
                        @include('buttons.destroy', ['resource' => 'user-achievements', 'item' => $userAchievement, 'size' => 'extraSmall'])
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p>No achievements awarded!</p>
@endif
