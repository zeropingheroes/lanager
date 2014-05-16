@if(count($awards))
	{{ Table::open() }}

	<?php
	foreach( $awards as $award )
	{
		$name = ( $award->achievement->description ) ? '<abbr title="' .  e($award->achievement->description) . '">' . e($award->achievement->name) . '</abbr>' : e($award->achievement->name);

		$lan = ( $award->lan ) ? $award->lan->name : '';

		$tableBody[] = array(
			'name'	=> $name,
			'lan'	=> $lan,
		);
	}
	?>

	{{ Table::body($tableBody) }}
	{{ Table::close() }}

@else
	No achievements awarded!
@endif