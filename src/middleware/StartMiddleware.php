<?php

namespace Hiraeth\Session;

use RuntimeException;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Dflydev\FigCookies\Cookies;
use Dflydev\FigCookies\SetCookie;
use Dflydev\FigCookies\FigResponseCookies;

/**
 * A middlware to start sessions
 */
class StartMiddleware implements Middleware
{
	/**
	 * The session cookie options.
	 *
	 * @var array<string, mixed>
	 */
	protected $options = array();


	/**
	 * Options to use when starting the session
	 *
	 * @var array<string, mixed>
	 */
	protected $startOptions = [
		'use_cookies'      => FALSE,
		'use_trans_sid'    => FALSE,
		'use_only_cookies' => TRUE
	];


	/**
	 * Create a new instance of the middleware
	 *
	 * @param array<string, mixed> $options The cookie options to use
	 */
	public function __construct(array $options = [])
	{
		$this->options = array_change_key_case($options, CASE_LOWER) + [
			'name' => ini_get('session.name')
		];
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
			$cookies    = Cookies::fromRequest($request);
			$session_id = $cookies->get($this->options['name']);

			if ($session_id) {
				session_id($session_id->getValue());
			}

			if (session_start($this->startOptions)) {
				$response = $handler->handle($request);

				if (session_status() === PHP_SESSION_NONE) {
					throw new RuntimeException('Session closed unexpectedly before response.');
				}

				$defaults = array_change_key_case(session_get_cookie_params(), CASE_LOWER);
				$options  = $this->options + $defaults;
				$cookie   = SetCookie::create($this->options['name'])
					->withValue(session_id() ?: NULL)
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

		return $handler->handle($request);
	}
}
