<?php

namespace Hiraeth\Session\Twig;

use Twig;
use Hiraeth\Session;

/**
 * {@inheritDoc}
 */
class ManagerExtension extends Twig\Extension\AbstractExtension implements Session\ManagedInterface, Twig\Extension\GlobalsInterface
{
	use Session\ManagedTrait;

	/**
	 * {@inheritDoc}
	 */
	public function getFunctions()
	{
		if ($this->hasSession()) {
			return [new Twig\TwigFunction('session', function($name) {
				return $this->getSessionManager()->getSegment($name);
			})];
		}

		return array();
	}


	/**
	 * {@inheritDoc}
	 *
	 * @return mixed[] array
	 */
	public function getGlobals()
	{
		if ($this->hasSession()) {
			return ['session' => $this->session];
		}

		return array();
	}
}
