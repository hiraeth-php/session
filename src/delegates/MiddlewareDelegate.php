<?php

namespace Hiraeth\Session;

use Hiraeth;
use Ellipse;
use SessionHandlerInterface;

/**
 * {@inheritDoc}
 */
class MiddlewareDelegate implements Hiraeth\Delegate
{
	/**
	 * {@inheritDoc}
	 */
	static public function getClass(): string
	{
		return StartSessionMiddleware::class;
	}


	/**
	 * Create a new instance of the delegate
	 */
	public function __construct(SessionHandlerInterface $handler = NULL)
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
