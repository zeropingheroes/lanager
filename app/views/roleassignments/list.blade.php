@if(count($roleAssignments))
	{{ Table::open(array('class' => 'role-assignments')) }}
	<?php
	foreach( $roleAssignments as $roleAssignment )
	{
		$tableBody[] = array(
			'user'		=> $roleAssignment->user->username,
			'role'		=> $roleAssignment->role->name,
			'controls'	=> HTML::resourceDelete('role-assignments',$roleAssignment->id, '', 'trash'),
		);
	}
	?>
	{{ Table::body($tableBody) }}
	{{ Table::close() }}
@else
	No roles assigned!
@endif
