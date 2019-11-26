<?php

namespace Hiraeth\Session;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;

/**
 * {@inheritDoc}
 */
class CsrfTokenMiddleware implements MiddlewareInterface
{
	/**
	 * Methods which require CSRF validation
	 *
	 * @var array
	 */
	protected static $methods = ['POST', 'PUT', 'DELETE'];


	/**
	 * A PSR-17 response factory
	 *
	 * @var ResponseFactory
	 */
	protected $factory = NULL;


	/**
	 * The session manager
	 *
	 * @var Manager
	 */
	protected $manager = NULL;


	/**
	 * Create a new instance of the middleware
	 */
	public function __construct(Manager $manager, ResponseFactory $factory)
	{
		$this->manager = $manager;
		$this->factory = $factory;
	}


	/**
	 * {@inheritDoc}
	 */
	public function process(Request $request, RequestHandler $handler): Response
	{
		if ($this->manager->isStarted() && in_array($request->getMethod(), static::$methods)) {

			$csrf_value = $request->getParsedBody()['csrf::token'] ?? '';
			$csrf_token = $this->manager->getCsrfToken();

			if (!$csrf_token->isValid($csrf_value)) {
				return $this->factory->createResponse(400);
			}
		}

		return $handler->handle($request);
	}
}
