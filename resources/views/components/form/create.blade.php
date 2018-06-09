<form method="POST" action="{{ $route }}" accept-charset="UTF-8" @if(isset($enctype)) enctype="{{$enctype}}" @endif>
    {{ csrf_field() }}