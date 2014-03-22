@extends('layouts.default')
@section('content')
<h3>Shouts</h3>
@include('shout.form')
@include('shout.list')
{{ $shouts->links() }}
@endsection