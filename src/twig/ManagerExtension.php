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
	public function getFunctions(): array
	{
		if ($this->hasSession()) {
			return [new Twig\TwigFunction(
				'session',
				fn($name) => $this->getSessionManager()->getSegment($name)
			)];
		}

		return [];
	}


	/**
	 * {@inheritDoc}
	 *
	 * @return mixed[] array
	 */
	public function getGlobals(): array
	{
		if ($this->hasSession()) {
			return ['session' => $this->session];
		}

		return [];
	}
}
