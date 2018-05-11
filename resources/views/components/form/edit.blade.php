<form method="POST" action="{{ $route }}" accept-charset="UTF-8">
    {{ method_field('PUT') }}
    {{ csrf_field() }}