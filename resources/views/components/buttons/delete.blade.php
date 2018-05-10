{{--TODO: make form inline--}}
<form action="{{ $route }}" method="POST" class="pull-left">
    <input type="hidden" name="_method" value="DELETE">
    {{ csrf_field() }}
    <button type="submit" class="btn btn-danger btn-sm" title="@lang('title.delete')"><span class="oi oi-trash" aria-hidden="true"></span></button>
</form>