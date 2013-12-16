@extends('layout')
@section('content')

<h2>Installation Completed</h2>

<p>To start using the LANager, in the file <em>/app/config/packages/zeropingheroes/lanager-core/config.php</em> set the following:</p>

<pre>
'installationCompleted'	=> true,
</pre>

<p>Once complete, you may {{ link_to('/', 'continue to the LANager') }}</p>
