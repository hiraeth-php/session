<?php

namespace Hiraeth\Session;

use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;

/**
 * {@inheritDoc}
 */
class CsrfTokenMiddleware implements Middleware, ManagedInterface
{
	use ManagedTrait;

	/**
	 * Methods which require CSRF validation
	 *
	 * @var array
	 */
	protected static $methods = ['POST', 'PUT', 'DELETE'];


	/**
	 * A PSR-17 response factory
	 *
	 * @var ResponseFactory|null
	 */
	protected $factory = NULL;



	/**
	 * Create a new instance of the middleware
	 */
	public function __construct(ResponseFactory $factory)
	{
		$this->factory = $factory;
	}


	/**
	 * {@inheritDoc}
	 */
	public function process(Request $request, RequestHandler $handler): Response
	{
		if ($this->hasSession() && in_array($request->getMethod(), static::$methods)) {

			$csrf_value = $request->getParsedBody()['csrf::token'] ?? '';
			$csrf_token = $this->session->getCsrfToken();

			if (!$csrf_token->isValid($csrf_value)) {
				return $this->factory->createResponse(400);
			}
		}

		return $handler->handle($request);
	}
}
