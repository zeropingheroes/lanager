@if(count($awards))
	{{ Table::open(array('class' => 'achievements')) }}
	<?php
	foreach( $awards as $award )
	{
		$name = ( $award->achievement->description ) ? '<abbr title="' .  e($award->achievement->description) . '">' . e($award->achievement->name) . '</abbr>' : e($award->achievement->name);

		$lan = ( $award->lan ) ? $award->lan->name : '';

		$lanDate = ( $award->lan ) ? date('M Y', strtotime($award->lan->start)) : '';

		if( Authority::can( 'manage', 'awards' ) )
		{
			$controls = HTML::resourceDelete('awards', $award->id, 'Delete');

			$tableBody[] = array(
					'name'		=> $name,
					'lan'		=> $lan,
					'lan_date'	=> $lanDate,
					'controls'	=> $controls
				);
		}
		else
		{
			$tableBody[] = array(
				'name'		=> $name,
				'lan'		=> $lan,
				'lan_date'	=> $lanDate,
			);			
		}
		
	}
	?>
	{{ Table::body($tableBody) }}
	{{ Table::close() }}
@else
	No achievements awarded!
@endif
