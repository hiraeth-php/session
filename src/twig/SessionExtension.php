<?php

namespace Hiraeth\Session\Twig;

use Hiraeth\Session;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

/**
 *
 */
class SessionExtension extends AbstractExtension implements GlobalsInterface
{
	/**
	 *
	 */
	public function __construct(Session\ManagerInterface $manager)
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
