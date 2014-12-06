@if(count($userRoles))
	<?php
	foreach( $userRoles as $userRole )
	{
		$tableBody[] = array(
			'user'		=> $userRole->user->username,
			'role'		=> $userRole->role->name,
			'controls'	=> HTML::button('user-roles.destroy',$userRole->id, ['value' => '']),
		);
	}
	?>
	{{ Table::withContents($tableBody) }}
@else
	No roles assigned!
@endif
