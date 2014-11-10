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
			$controls = HTML::button('awards.destroy', $award->id, ['value' => 'Revoke', 'size' => 'xs']);

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
	{{ Table::withContents($tableBody) }}
	
@else
	No achievements awarded!
@endif
