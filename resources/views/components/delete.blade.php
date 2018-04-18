<form action="{{ $route }}" method="POST">
    <input type="hidden" name="_method" value="DELETE">
    {{ csrf_field() }}
    <button type="submit" class="btn btn-danger btn-sm"><span class="oi oi-trash" title="Delete" aria-hidden="true"></span></button>
</form>