<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Application Debug Mode
	|--------------------------------------------------------------------------
	|
	| When your application is in debug mode, detailed error messages with
	| stack traces will be shown on every error that occurs within your
	| application. If disabled, a simple generic error page is shown.
	|
	*/

	'debug' => false,

	/*
	|--------------------------------------------------------------------------
	| Application URL
	|--------------------------------------------------------------------------
	|
	| This URL is used by the console to properly generate URLs when using
	| the Artisan command line tool. You should set this to the root of
	| your application so that it is used when running Artisan tasks.
	|
	*/

	'url' => 'http://localhost',

	/*
	|--------------------------------------------------------------------------
	| Application Timezone
	|--------------------------------------------------------------------------
	|
	| Here you may specify the default timezone for your application, which
	| will be used by the PHP date and date-time functions.
	|
	| Please set according to your location to ensure correct time display.
	| For a list of valid timezones, see here:
	| http://php.net/manual/en/timezones.php
	*/

	'timezone' => 'Europe/London',

	/*
	|--------------------------------------------------------------------------
	| Application Locale Configuration
	|--------------------------------------------------------------------------
	|
	| The application locale determines the default locale that will be used
	| by the translation service provider. You are free to set this value
	| to any of the locales which will be supported by the application.
	|
	*/

	'locale' => 'en',

	/*
	|--------------------------------------------------------------------------
	| Encryption Key
	|--------------------------------------------------------------------------
	|
	| This key is used by the Illuminate encrypter service and should be set
	| to a random, 32 character string, otherwise these encrypted strings
	| will not be safe. Please do this before deploying an application!
	|
	*/

	'key' => '',
	'cipher' => MCRYPT_RIJNDAEL_256,

	/*
	|--------------------------------------------------------------------------
	| Autoloaded Service Providers
	|--------------------------------------------------------------------------
	|
	| The service providers listed here will be automatically loaded on the
	| request to your application. Feel free to add your own services to
	| this array to grant expanded functionality to your applications.
	|
	*/

	'providers' => array(

		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		'Illuminate\Auth\AuthServiceProvider',
		'Illuminate\Cache\CacheServiceProvider',
		'Illuminate\Session\CommandsServiceProvider',
		'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
		'Illuminate\Routing\ControllerServiceProvider',
		'Illuminate\Cookie\CookieServiceProvider',
		'Illuminate\Database\DatabaseServiceProvider',
		'Illuminate\Encryption\EncryptionServiceProvider',
		'Illuminate\Filesystem\FilesystemServiceProvider',
		'Illuminate\Hashing\HashServiceProvider',
		'Illuminate\Html\HtmlServiceProvider',
		'Illuminate\Log\LogServiceProvider',
		'Illuminate\Mail\MailServiceProvider',
		'Illuminate\Database\MigrationServiceProvider',
		'Illuminate\Pagination\PaginationServiceProvider',
		'Illuminate\Queue\QueueServiceProvider',
		'Illuminate\Redis\RedisServiceProvider',
		'Illuminate\Remote\RemoteServiceProvider',
		'Illuminate\Auth\Reminders\ReminderServiceProvider',
		'Illuminate\Database\SeedServiceProvider',
		'Illuminate\Session\SessionServiceProvider',
		'Illuminate\Translation\TranslationServiceProvider',
		'Illuminate\Validation\ValidationServiceProvider',
		'Illuminate\View\ViewServiceProvider',
		'Illuminate\Workbench\WorkbenchServiceProvider',

		'Authority\AuthorityL4\AuthorityL4ServiceProvider',
		'VTalbot\Markdown\MarkdownServiceProvider',
		'Bootstrapper\BootstrapperServiceProvider',
		'Zeropingheroes\SteamBrowserProtocol\SteamBrowserProtocolServiceProvider',
		'ExpressiveDateServiceProvider',
		'Zeropingheroes\Duration\DurationServiceProvider',
		'Zeropingheroes\Timespan\TimespanServiceProvider',
		'Zeropingheroes\Lanager\Users\UserImportServiceProvider',
		'Krucas\Notification\NotificationServiceProvider',
		'Mews\Purifier\PurifierServiceProvider',
		'Dingo\Api\Provider\ApiServiceProvider',
		// 'Barryvdh\Debugbar\ServiceProvider',
	),

	/*
	|--------------------------------------------------------------------------
	| Service Provider Manifest
	|--------------------------------------------------------------------------
	|
	| The service provider manifest is used by Laravel to lazy load service
	| providers which are not needed for each request, as well to keep a
	| list of all of the services. Here, you may set its storage spot.
	|
	*/

	'manifest' => storage_path().'/meta',

	/*
	|--------------------------------------------------------------------------
	| Class Aliases
	|--------------------------------------------------------------------------
	|
	| This array of class aliases will be registered when this application
	| is started. However, feel free to register as many as you wish as
	| the aliases are "lazy" loaded so they don't hinder performance.
	|
	*/

	'aliases' => array(

		'App'             => 'Illuminate\Support\Facades\App',
		'Artisan'         => 'Illuminate\Support\Facades\Artisan',
		'Auth'            => 'Illuminate\Support\Facades\Auth',
		'Blade'           => 'Illuminate\Support\Facades\Blade',
		'Cache'           => 'Illuminate\Support\Facades\Cache',
		'ClassLoader'     => 'Illuminate\Support\ClassLoader',
		'Config'          => 'Illuminate\Support\Facades\Config',
		'Controller'      => 'Illuminate\Routing\Controller',
		'Cookie'          => 'Illuminate\Support\Facades\Cookie',
		'Crypt'           => 'Illuminate\Support\Facades\Crypt',
		'DB'              => 'Illuminate\Support\Facades\DB',
		'Eloquent'        => 'Illuminate\Database\Eloquent\Model',
		'Event'           => 'Illuminate\Support\Facades\Event',
		'File'            => 'Illuminate\Support\Facades\File',
		'Form'            => 'Illuminate\Support\Facades\Form',
		'Hash'            => 'Illuminate\Support\Facades\Hash',
		'HTML'            => 'Illuminate\Support\Facades\HTML',
		'Input'           => 'Illuminate\Support\Facades\Input',
		'Lang'            => 'Illuminate\Support\Facades\Lang',
		'Log'             => 'Illuminate\Support\Facades\Log',
		'Mail'            => 'Illuminate\Support\Facades\Mail',
		'Paginator'       => 'Illuminate\Support\Facades\Paginator',
		'Password'        => 'Illuminate\Support\Facades\Password',
		'Queue'           => 'Illuminate\Support\Facades\Queue',
		'Redirect'        => 'Illuminate\Support\Facades\Redirect',
		'Redis'           => 'Illuminate\Support\Facades\Redis',
		'Request'         => 'Illuminate\Support\Facades\Request',
		'Response'        => 'Illuminate\Support\Facades\Response',
		'Route'           => 'Illuminate\Support\Facades\Route',
		'Schema'          => 'Illuminate\Support\Facades\Schema',
		'Seeder'          => 'Illuminate\Database\Seeder',
		'Session'         => 'Illuminate\Support\Facades\Session',
		'SoftDeletingTrait' => 'Illuminate\Database\Eloquent\SoftDeletingTrait',
		'SSH'             => 'Illuminate\Support\Facades\SSH',
		'Str'             => 'Illuminate\Support\Str',
		'URL'             => 'Illuminate\Support\Facades\URL',
		'Validator'       => 'Illuminate\Support\Facades\Validator',
		'View'            => 'Illuminate\Support\Facades\View',


		'SteamBrowserProtocol'	=> 'Zeropingheroes\SteamBrowserProtocol\Facades\SteamBrowserProtocol',
		'Duration'				=> 'Zeropingheroes\Duration\Facades\Duration',
		'Timespan'				=> 'Zeropingheroes\Timespan\Facades\Timespan',
		'UserImport'			=> 'Zeropingheroes\Lanager\Users\Facades\UserImport',

		'Accordion'				=> 'Bootstrapper\Facades\Accordion',
		'Alert'					=> 'Bootstrapper\Facades\Alert',
		'Badge'					=> 'Bootstrapper\Facades\Badge',
		'Breadcrumb'			=> 'Bootstrapper\Facades\Breadcrumb',
		'Button'				=> 'Bootstrapper\Facades\Button',
		'ButtonGroup'			=> 'Bootstrapper\Facades\ButtonGroup',
		'Carousel'				=> 'Bootstrapper\Facades\Carousel',
		'ControlGroup'			=> 'Bootstrapper\Facades\ControlGroup',
		'DropdownButton'		=> 'Bootstrapper\Facades\DropdownButton',
		'Form'					=> 'Bootstrapper\Facades\Form',
		'Helpers'				=> 'Bootstrapper\Facades\Helpers',
		'Icon'					=> 'Bootstrapper\Facades\Icon',
		'InputGroup'			=> 'Bootstrapper\Facades\InputGroup',
		'Image'					=> 'Bootstrapper\Facades\Image',
		'Label'					=> 'Bootstrapper\Facades\Label',
		'MediaObject'			=> 'Bootstrapper\Facades\MediaObject',
		'Modal'					=> 'Bootstrapper\Facades\Modal',
		'Navbar'				=> 'Bootstrapper\Facades\Navbar',
		'Navigation'			=> 'Bootstrapper\Facades\Navigation',
		'Panel'					=> 'Bootstrapper\Facades\Panel',
		'ProgressBar'			=> 'Bootstrapper\Facades\ProgressBar',
		'Tabbable'				=> 'Bootstrapper\Facades\Tabbable',
		'Table'					=> 'Bootstrapper\Facades\Table',
		'Thumbnail'				=> 'Bootstrapper\Facades\Thumbnail',
		
		'Authority'				=> 'Authority\AuthorityL4\Facades\Authority',
		'Notification'			=> 'Krucas\Notification\Facades\Notification',
		'Markdown'				=> 'VTalbot\Markdown\Facades\Markdown',
		'Purifier'				=> 'Mews\Purifier\Facades\Purifier',
		'API'					=> 'Dingo\Api\Facade\API',
		// 'Debugbar'				=> 'Barryvdh\Debugbar\Facade',
	),

);
