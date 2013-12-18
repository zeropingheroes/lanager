@extends('layout')
@section('content')

<h2>Installation Completed</h2>

<p><strong>Before using the LANager</strong>, please edit the following config files:</p>

<p><em>/app/config/packages/zeropingheroes/lanager-core/config.php</em></p>

<pre>
'installationCompleted'	=> true,
</pre>

<p><em>/app/config/session.php</em></p>

<pre>
'driver'	=> 'database',
</pre>

<p>Once you have made these changes, you may {{ link_to('/', 'continue to the LANager!') }}</p>
