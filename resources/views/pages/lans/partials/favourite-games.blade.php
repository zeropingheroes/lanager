@foreach($lan->userFavouriteGames()->distinct()->get(['favouriteable_id', 'favouriteable_type']) as $favourite)
    <div>
        <img src="{{ $favourite->favouriteable->logo('small') }}">
        {{ $favourite->favouriteable->name }}
    </div>
@endforeach
