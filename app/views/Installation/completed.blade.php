@extends('layout')
@section('content')

<h2>Installation Completed</h2>

<p><strong>Before you can use the LANager</strong>, you will need to complete the following:</p>

<ol>
	<li>
		<p>Edit <em>/app/config/packages/zeropingheroes/lanager-core/config.php</em></p>
		<pre>
		'installationCompleted'	=> true,
		</pre>
	</li>
	<li>
		<p>Edit <em>/app/config/session.php</em></p>
		<pre>
		'driver'	=> 'database',
		</pre>
	</li>
	<li>
		<p>Run <em>artisan steam:import-apps</em> in the lanager directory</p>
		<pre>
		$ php artisan steam:import-apps
		</pre>
	</li>
	<li>
		<p>Schedule <em>SteamImportUserStates</em> to run every minute</p>
		<?php
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				echo 'Add a task for <pre>SteamImportUserStates.bat</pre> in ';
				echo link_to('http://support.microsoft.com/kb/226795', 'Task Scheduler', array('target' => '_blank'));
			} else {
				echo 'From a terminal run <pre>crontab -e</pre> and add the following to the end of the file:';
				echo '<pre>*/1 * * * * /path/to/lanager/SteamImportUserStates.sh >> /dev/null 2>&1</pre>';
			}
		?>
	</li>
</ol>
<br>
<p>Once you have made these changes, you may {{ link_to('/', 'continue to the LANager!') }}</p>
