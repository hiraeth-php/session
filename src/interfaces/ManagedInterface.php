<?php

namespace Hiraeth\Session;

/**
 * An interface for setting the session manager
 */
interface ManagedInterface
{
	/**
	 * Set the session manager for this object
	 */
	public function setSessionManager(Manager $session): object;
}
