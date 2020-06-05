<form method="POST" action="{{ route('lans.lan-games.store', ['lan' => $lan]) }}" accept-charset="UTF-8">
    {{ csrf_field() }}
    <div class="row no-gutters">
        <div class="col">
            <input class="form-control"
                   type="search"
                   id="game_name"
                   name="game_name"
                   placeholder="@lang('phrase.create-lan-game', ['lan' => $lan->name])">
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" type="submit">
                @lang('title.submit')
            </button>
        </div>
    </div>
</form>
