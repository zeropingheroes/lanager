@extends('lanager-core::layouts.default')
@section('content')
<h3>Shouts</h3>
@include('lanager-core::shout.form')
@include('lanager-core::shout.list')
{{ $shouts->links() }}
@endsection