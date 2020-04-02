<?php

namespace Hiraeth\Session;

use Aura\Session;

/**
 * Enables setting session manager
 */
trait ManagedTrait
{
	/**
	 * The session manager
	 *
	 * @var Manager|null
	 */
	protected $session = NULL;


	/**
	 * {@inheritDoc}
	 */
	public function getSessionManager(): ?Manager
	{
		return $this->session;
	}



	/**
	 * {@inheritDoc}
	 * TODO: Change return type to "self" for 7.4
	 */
	public function setSessionManager(Manager $session): ManagedInterface
	{
		$this->session = $session;

		return $this;
	}


	/**
	 * Determine whether or not the session mangaer and session exist
	 */
	protected function hasSession(): bool
	{
		return $this->session && $this->session->isStarted();
	}


	/**
	 * Get a session segment
	 */
	protected function session(string $name): Session\Segment
	{
		return $this->session->getSegment($name);
	}

}
