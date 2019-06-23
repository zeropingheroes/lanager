<table class="table table-striped">
    <tbody>
    @foreach($achievements as $achievement)
        @can('view', $achievement)
            <tr>
                <td>
                    <a href="{{ route('achievements.show', ['achievement' => $achievement])}}">
                        @if($achievement->image_filename)
                            <img src="/storage/images/achievements/{{ $achievement->image_filename }}" height="32px" width="32px">
                        @endif
                        {{ $achievement->name }}
                    </a>
                </td>
                @canany(['edit', 'delete'], $achievement)
                <td class="text-right pr-0">
                    @component('components.actions-dropdown')
                        @include('components.actions-dropdown.edit', ['item' => $achievement])
                        @include('components.actions-dropdown.delete', ['item' => $achievement])
                    @endcomponent
                </td>
                @endcanany
            </tr>
        @endcan
    @endforeach
    </tbody>
</table>
