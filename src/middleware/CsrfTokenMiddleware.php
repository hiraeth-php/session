<?php

namespace Hiraeth\Session;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;

/**
 *
 */
class CsrfTokenMiddleware implements MiddlewareInterface
{
	/**
	 *
	 */
	protected $responseFactory = NULL;


	/**
	 *
	 */
	protected $sessionManager = NULL;


	/**
	 *
	 */
	public function __construct(ManagerInterface $session_manager, ResponseFactory $response_factory)
	{
		$this->sessionManager  = $session_manager;
		$this->responseFactory = $response_factory;
	}


	/**
	 *
	 */
	public function process(Request $request, RequestHandler $handler): Response
	{
		if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'])) {

			$csrf_value = $request->getParsedBody()['csrf::token'] ?? '';
			$csrf_token = $this->sessionManager->getCsrfToken();

			if (!$csrf_token->isValid($csrf_value)) {
				return $this->responseFactory->createResponse(400);
			}
		}

		return $handler->handle($request);
	}
}
