<?php

namespace Hiraeth\Session;

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
	public function setSessionManager(Manager $session): object
	{
		$this->session = $session;
	}


	/**
	 * Get the session manager
	 */
	protected function getSession(): ?Manager
	{
		return $this->session;
	}


	/**
	 * Determine whether or not the session mangaer and session exist
	 */
	protected function hasSession(): bool
	{
		return $this->session && $this->session->isStarted();
	}
}
