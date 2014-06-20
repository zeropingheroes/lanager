<?php namespace Zeropingheroes\Lanager\Users;

use Illuminate\Support\ServiceProvider;

class UserImportServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->app['UserImport'] = $this->app->share(function($app)
		{
			return $app->make('Zeropingheroes\Lanager\Users\UserImport');
		});
		$this->app->booting(function()
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('UserImport'   ,'Zeropingheroes\Lanager\Users\Facades\UserImport');
		});
	}

}
