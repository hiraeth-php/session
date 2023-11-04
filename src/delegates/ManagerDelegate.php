<?php

namespace Hiraeth\Session;

use Hiraeth;
use Aura;

/**
 * {@inheritDoc}
 */
class ManagerDelegate implements Hiraeth\Delegate
{
	/**
	 * {@inheritDoc}
	 */
	static public function getClass(): string
	{
		return Manager::class;
	}


	/**
	 * {@inheritDoc}
	 */
	public function __invoke(Hiraeth\Application $app): object
	{
		$phpfunc = new Aura\Session\Phpfunc();
		$randval = new Aura\Session\Randval();
		$cookies = array();

		return $app->share(new Manager(
			 new Aura\Session\SegmentFactory,
			 new Aura\Session\CsrfTokenFactory($randval),
			 $phpfunc,
			 $cookies,
			 NULL
		));
	}
}
