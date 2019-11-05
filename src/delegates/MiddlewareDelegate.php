<?php

namespace Hiraeth\Session;

use Hiraeth;
use Ellipse;
use SessionHandlerInterface;

/**
 *
 */
class MiddlewareDelegate implements Hiraeth\Delegate
{
	/**
	 *
	 */
	protected $caches = array();


	/**
	 * Get the class for which the delegate operates.
	 *
	 * @static
	 * @access public
	 * @return string The class for which the delegate operates
	 */
	static public function getClass(): string
	{
		return StartSessionMiddleware::class;
	}


	/**
	 *
	 */
	public function __construct(SessionHandlerInterface $handler = NULL)
	{
		if ($handler) {
			session_set_save_handler($handler);
		}
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
		return new StartSessionMiddleware([
			'lifetime' => $app->getEnvironment('SESSION.TTL', ini_get('session.cookie_lifetime')),
			'httponly' => $app->getEnvironment('SESSION.HTTPONLY', ini_get('session.cookie_httponly')),
			'domain'   => $app->getEnvironment('SESSION.DOMAIN', ini_get('session.cookie_domain')),
			'secure'   => $app->getEnvironment('SESSION.SECURE', ini_get('session.cookie_secure')),
			'path'     => $app->getEnvironment('SESSION.PATH', ini_get('session.cookie_path')),
			'name'     => $app->getEnvironment('SESSION.NAME', ini_get('session.name'))
		]);
	}
}
