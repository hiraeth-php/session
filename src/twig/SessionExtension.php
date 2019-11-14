<?php

namespace Hiraeth\Session\Twig;

use Hiraeth\Session;

use Twig\Extension;
use Twig\TwigFunction;

/**
 *
 */
class SessionExtension extends Extension\AbstractExtension implements Extension\GlobalsInterface
{
	/**
	 *
	 */
	protected $manager = NULL;


	/**
	 *
	 */
	public function __construct(Session\Manager $manager)
	{
		$this->manager = $manager;
	}


	/**
	 *
	 */
	public function getFunctions()
	{
		if (!$this->manager->isStarted()) {
			return array();
		}

		return [
			new TwigFunction('session', [$this->manager, 'getSegment'])
		];
	}


	/**
	 *
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
