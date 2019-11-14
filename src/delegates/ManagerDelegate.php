<?php

namespace Hiraeth\Session;

use Hiraeth;
use Aura;

/**
 *
 */
class ManagerDelegate implements Hiraeth\Delegate
{
	/**
	 *
	 */
	protected $caches = array();


	/**
	 *
	 */
	protected $factory = NULL;


	/**
	 * Get the class for which the delegate operates.
	 *
	 * @static
	 * @access public
	 * @return string The class for which the delegate operates
	 */
	static public function getClass(): string
	{
		return Aura\Session\Session::class;
	}


	/**
	 *
	 */
	public function __construct(Aura\Session\SessionFactory $factory)
	{
		$this->factory = $factory;
	}


	/**
	 * Get the instance of the class for which the delegate operates.
	 *
	 * @access public
	 * @param Hiraeth\Application $app The application instance for which the delegate operates
	 * @return object The instance of the class for which the delegate operates
	 */
	public function __invoke(Hiraeth\Application $app): object
	{
		return $app->share($this->factory->newInstance([]));
	}
}
