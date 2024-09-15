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
                    <form action="{{ route('lans.events.signups.destroy',
                    ['lan' => $signup->event->lan,
                    'event' => $signup->event,
                    'signup' => $signup]) }}"
                          method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <button type="submit"
                                class="btn btn-danger btn-sm"
                                title="@lang('title.delete')"
                                onclick="submitDeletionForm(event)">
                            Delete
                        </button>
                    </form>
                @endcan
            </td>
        </tr>
    @endforeach
</table>
