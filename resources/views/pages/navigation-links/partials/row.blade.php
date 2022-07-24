<tr>
    <td>
        @if( $navigationLink->parent_id )
            &ndash;
        @endif
        {{ $navigationLink->title }}
    </td>
    <td>
        <a href="{{ $navigationLink->url }}" target="_blank">{{ $navigationLink->url }}</a>
    </td>
    <td>
        {{ $navigationLink->position }}
    </td>
    <td>
        @component('components.actions-dropdown')
            @include('components.actions-dropdown.edit', ['item' => $navigationLink])
            @include('components.actions-dropdown.delete', ['item' => $navigationLink])
        @endcomponent
    </td>
</tr>
