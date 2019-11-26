<?php

namespace Hiraeth\Session\Twig;

use Twig;
use Hiraeth\Session;

/**
 * {@inheritDoc}
 */
class SessionExtension extends Twig\Extension\AbstractExtension implements Twig\Extension\GlobalsInterface
{
	/**
	 * The session manager
	 *
	 * @var Session\Manager
	 */
	protected $manager = NULL;


	/**
	 * Create a new instance of the extension
	 */
	public function __construct(Session\Manager $manager)
	{
		$this->manager = $manager;
	}


	/**
	 * {@inheritDoc}
	 */
	public function getFunctions()
	{
		if (!$this->manager->isStarted()) {
			return array();
		}

		return [
			new Twig\TwigFunction('session', [$this->manager, 'getSegment'])
		];
	}


	/**
	 * {@inheritDoc}
	 */
	public function getGlobals()
	{
		if (!$this->manager->isStarted()) {
			return array();
		}

		return [
			'session' => $this->manager
		];
	}
}
