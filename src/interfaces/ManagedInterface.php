<?php

namespace Hiraeth\Session;

/**
 * An interface for setting the session manager
 */
interface ManagedInterface
{
	/**
	 * Get the session manager for this object
	 */
	public function getSessionManager(): ?Manager;


	/**
	 * Set the session manager for this object
	 */
	public function setSessionManager(Manager $session): self;
}
