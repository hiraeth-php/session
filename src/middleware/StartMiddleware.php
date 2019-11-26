<?php

namespace Hiraeth\Session;

use RuntimeException;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Dflydev\FigCookies\SetCookie;
use Dflydev\FigCookies\FigResponseCookies;

/**
 * A middlware to start sessions
 */
class StartMiddleware
{
	/**
	 * The session cookie options.
	 *
	 * @var array
	 */
	protected $options = array();


	/**
	 * Options to use when starting the session
	 *
	 * @var array
	 */
	protected $startOptions = [
		'use_cookies'      => FALSE,
		'use_trans_sid'    => FALSE,
		'use_only_cookies' => TRUE
	];


	/**
	 * Create a new instance of the middleware
	 *
	 * @param array $options The cookie options to use
	 */
	public function __construct(array $options = [])
	{
		$this->options = array_change_key_case($options, CASE_LOWER);
	}


	/**
	 * {@inheritDoc}
	 */
	public function process(Request $request, RequestHandler $handler): Response
	{
		if (session_status() === PHP_SESSION_ACTIVE) {
			throw new RuntimeException('Session could not be started, already opened.');
		}

		if (session_status() !== PHP_SESSION_DISABLED) {
			$cookies      = $request->getCookieParams();
			$session_name = $this->options['name']  ?? ini_get('session.name');
			$session_id   = $cookies[$session_name] ?? NULL;

			if ($session_id) {
				session_id($session_id);
			}

			if (session_start([$this->startOptions])) {
				session_name($session_name);

				$response = $handler->handle($request);

				if (session_status() === PHP_SESSION_NONE) {
					throw new RuntimeException('Session closed unexpectedly before response.');
				}

				$session_id = session_id();
				$defaults   = array_change_key_case(session_get_cookie_params(), CASE_LOWER);
				$options    = $this->options + $defaults;
				$cookie     = SetCookie::create($session_name)
					->withValue($session_id)
					->withPath($options['path'])
					->withDomain($options['domain'])
					->withSecure($options['secure'])
					->withMaxAge($options['lifetime'])
					->withHttpOnly($options['httponly'])
				;

				if ($options['lifetime'] > 0) {
					$cookie = $cookie->withExpires(time() + $options['lifetime']);
				}

				session_write_close();

				return FigResponseCookies::set($response, $cookie);

			}

			throw new RuntimeException('Failed to start session.');
		}
	}
}
