<?php

namespace Hiraeth\Session;

/**
 * An interface for setting the session manager
 */
interface ManagerSetter
{
	/**
	 * Set the session manager for this object
	 */
	public function setSessionManager(Manager $manager): object;
}
