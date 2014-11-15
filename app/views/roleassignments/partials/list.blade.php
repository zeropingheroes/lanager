@if(count($roleAssignments))
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
	{{ Table::withContents($tableBody) }}
@else
	No roles assigned!
@endif
