<?php

namespace Hiraeth\Session;

use Hiraeth;

/**
 * {@inheritDoc}
 */
class ManagerSetterProvider implements Hiraeth\Provider
{
	/**
	 * {@inheritDoc}
	 */
	static public function getInterfaces(): array
	{
		return [
			ManagerSetter::class
		];
	}


	/**
	 * {@inheritDoc}
	 */
	public function __invoke(object $instance, Hiraeth\Application $app): object
	{
		$instance->setSessionManager($app->get(Manager::class));

		return $instance;
	}
}
