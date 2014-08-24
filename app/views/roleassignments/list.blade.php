@if(count($roleAssignments))
	{{ Table::open(array('class' => 'role-assignments')) }}
	<?php
	foreach( $roleAssignments as $roleAssignment )
	{
		$tableBody[] = array(
			'user'		=> $roleAssignment->user->username,
			'role'		=> $roleAssignment->role->name,
			'controls'	=> HTML::button('role-assignments.destroy',$roleAssignment->id, ['value' => '']),
		);
	}
	?>
	{{ Table::body($tableBody) }}
	{{ Table::close() }}
@else
	No roles assigned!
@endif
