<form method="POST"
      action="{{ route('api-tokens.destroy', ['api_token' => $token->id]) }}"
      class="d-inline"
>
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger" onclick="confirmFormSubmit(event)">
        <i class="fa-solid fa-trash"></i> @lang('title.delete')
    </button>
</form>
