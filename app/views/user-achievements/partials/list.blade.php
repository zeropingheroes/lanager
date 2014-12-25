@if(count($userAchievements))

	<?php
	foreach( $userAchievements as $userAchievement )
	{
		$tableBody[] = array(
				'name'		=> '<abbr title="' . e($userAchievement->achievement->description) . '">' . e($userAchievement->achievement->name) . '</abbr>',
				'lan'		=> $userAchievement->lan->name,
				'lan_date'	=> date('M Y', strtotime($userAchievement->lan->start)),
			);		
	}
	?>
	{{ Table::withContents($tableBody) }}
	
@else
	No achievements awarded!
@endif
