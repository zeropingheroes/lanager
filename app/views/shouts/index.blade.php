@extends('layouts.default')
@section('content')
<h3>Shouts</h3>
@include('shouts.form')
@include('shouts.list')
{{ $shouts->links() }}
@endsection