<?php

namespace Hiraeth\Session;

use Hiraeth;

/**
 * {@inheritDoc}
 */
class ManagedInterfaceProvider implements Hiraeth\Provider
{
	/**
	 * {@inheritDoc}
	 */
	static public function getInterfaces(): array
	{
		return [
			ManagedInterface::class
		];
	}


	/**
	 * {@inheritDoc}
	 *
	 * @param ManagedInterface $instance
	 */
	public function __invoke(object $instance, Hiraeth\Application $app): object
	{
		$instance->setSessionManager($app->get(Manager::class));

		return $instance;
	}
}
