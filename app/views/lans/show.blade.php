@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	<p>Start: {{ $lan->start }}</p>
	<p>End: {{ $lan->end }}</p>
	
	<h3>Achievements Awarded</h3>
	@include('user-achievements.partials.list', ['userAchievements' => $lan->userAchievement])

	<br>

	{{ HTML::button('lans.create') }}
	{{ HTML::button('lans.edit', $lan->id) }}
	{{ HTML::button('lans.destroy', $lan->id) }}
@endsection				
