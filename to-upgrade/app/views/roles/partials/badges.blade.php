@forelse($roles as $role)
    {{ Badge::withContents($role->name) }}
@empty
@endforelse