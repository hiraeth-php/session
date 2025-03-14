<?php

namespace Hiraeth\Session;

use Hiraeth;
use SessionHandlerInterface;

/**
 * {@inheritDoc}
 */
class StartMiddlewareDelegate implements Hiraeth\Delegate
{
	/**
	 * {@inheritDoc}
	 */
	static public function getClass(): string
	{
		return StartMiddleware::class;
	}


	/**
	 * Create a new instance of the delegate
	 */
	public function __construct(?SessionHandlerInterface $handler = NULL)
	{
		if ($handler) {
			session_set_save_handler($handler);
		}
	}


	/**
	 * {@inheritDoc}
	 */
	public function __invoke(Hiraeth\Application $app): object
	{
		return new StartMiddleware([
			'lifetime' => $app->getEnvironment('SESSION_TTL',      ini_get('session.cookie_lifetime')),
			'httponly' => $app->getEnvironment('SESSION_HTTPONLY', ini_get('session.cookie_httponly')),
			'domain'   => $app->getEnvironment('SESSION_DOMAIN',   ini_get('session.cookie_domain')),
			'secure'   => $app->getEnvironment('SESSION_SECURE',   ini_get('session.cookie_secure')),
			'path'     => $app->getEnvironment('SESSION_PATH',     ini_get('session.cookie_path')),
			'name'     => $app->getEnvironment('SESSION_NAME',     ini_get('session.name'))
		]);
	}
}
