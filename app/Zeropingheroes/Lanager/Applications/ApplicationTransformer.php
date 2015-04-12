<?php namespace Zeropingheroes\Lanager\Applications;

use League\Fractal;

class ApplicationTransformer extends Fractal\TransformerAbstract {

	/**
	 * Transform resource into standard output format with correct typing
	 * @param  object BaseModel   Resource being transformed
	 * @return array              Transformed object array ready for output
	 */
	public function transform(Application $application)
	{
		return [
			'id'			=> (int) $application->id,
			'name'			=> $application->name,
			'steam_app_id'	=> $application->steam_app_id,
			'url'			=> $application->present()->url,
			'logo_small'	=> $application->present()->smallLogo,
			'logo_medium'	=> $application->present()->mediumLogo,
			'logo_large'	=> $application->present()->largeLogo,
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => (url().'/applications/'. $application->id),
				]
			],
		];
	}
}