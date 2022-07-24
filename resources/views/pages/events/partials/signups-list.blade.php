<table class="table table-striped">
    @foreach($event->signups as $signup)
        <tr>
            <td>
                @include('pages.users.partials.avatar-username', ['user' => $signup->user])
            </td>
            <td>
                @include('components.time-relative', ['datetime' => $signup->created_at])
            </td>
            <td>
                @can('delete', $signup)
                    @component('components.actions-dropdown')
                        <form action="{{ route('lans.events.signups.destroy',
                        ['lan' => $signup->event->lan,
                        'event' => $signup->event,
                        'signup' => $signup]) }}"
                              method="POST" class="confirm-deletion">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <a class="dropdown-item" href="#" onclick="$(this).closest('form').submit();">@lang('title.delete')</a>
                        </form>
                    @endcomponent
                @endcan
            </td>
        </tr>
    @endforeach
</table>
