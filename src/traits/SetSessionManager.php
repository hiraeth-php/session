<?php

namespace Hiraeth\Session;

/**
 * Enables setting session manager
 */
trait SetSessionManager
{
	/**
	 * The session manager
	 *
	 * @var Manager
	 */
	protected $sessionManager = NULL;


	/**
	 * Set the session manager for this object
	 */
	public function setSessionManager(Manager $manager): object
	{
		$this->sessionManager = $manager;
	}
}
