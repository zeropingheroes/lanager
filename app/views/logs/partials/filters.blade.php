<span class="pull-right">
	{{ 
		DropdownButton::normal('SAPI')
		->withContents(
			[
				['url' => route('logs.index', Input::except('sapi') + ['sapi' => '']),				'label' => 'Show All'],
				['url' => route('logs.index', Input::except('sapi') + ['sapi' => 'cli']),			'label' => 'CLI'],
				['url' => route('logs.index', Input::except('sapi') + ['sapi' => 'cli-server']),	'label' => 'CLI-Server'],
				['url' => route('logs.index', Input::except('sapi') + ['sapi' => 'fpm-fcgi']),		'label' => 'FPM-FCGI'],
				['url' => route('logs.index', Input::except('sapi') + ['sapi' => 'apache']),		'label' => 'Apache'],
				['url' => route('logs.index', Input::except('sapi') + ['sapi' => 'apache2handler']),'label' => 'Apache2handler'],
				['url' => route('logs.index', Input::except('sapi') + ['sapi' => 'cgi-fcgi']),		'label' => 'CGI-FCGI'],
			]
		)
	}}


    {{
        DropdownButton::normal('Min. Level')
        ->withContents(
            [
                ['url' => route('logs.index', Input::except('minLevel') + ['minLevel' => 'debug']),		'label' => 'Debug'],
                ['url' => route('logs.index', Input::except('minLevel') + ['minLevel' => 'info']),		'label' => 'Info'],
                ['url' => route('logs.index', Input::except('minLevel') + ['minLevel' => 'notice']),	'label' => 'Notice'],
                ['url' => route('logs.index', Input::except('minLevel') + ['minLevel' => 'warning']),	'label' => 'Warning'],
                ['url' => route('logs.index', Input::except('minLevel') + ['minLevel' => 'error']),		'label' => 'Error'],
                ['url' => route('logs.index', Input::except('minLevel') + ['minLevel' => 'critical']),	'label' => 'Critical'],
                ['url' => route('logs.index', Input::except('minLevel') + ['minLevel' => 'alert']),		'label' => 'Alert'],
                ['url' => route('logs.index', Input::except('minLevel') + ['minLevel' => 'emergency']),	'label' => 'Emergency'],
            ]
        )
    }}
</span>
