<?php

namespace Hiraeth\Session;

use RuntimeException;
use Hiraeth\Templates;

/**
 * Enables setting flash messages
 */
trait FlashTrait
{
	/**
	 *
	 */
	protected function flash($type, $message, array $context = array()): self
	{
		if (!$this instanceof ManagedInterface) {
			throw new RuntimeException(sprintf(
				'Flash is not supported, "%s" is not implemented by "%s"',
				ManagedInterface::class,
				get_class($this)
			));
		}

		if (!$this->getSessionManager()) {
			throw new RuntimeException(sprintf(
				'Flash is not supported, "%s" has no session manage registered',
				get_class($this)
			));
		}

		if ($message[0] == '@') {
			if (!$this instanceof Templates\ManagedInterface) {
				throw new RuntimeException(sprintf(
					'Flash templates are not supported, "%s" is not implemented by "%s"',
					Templates\ManagedInterface::class,
					get_class($this)
				));
			}

			if (!$this->getTemplatesManager()) {
				throw new RuntimeException(sprintf(
					'Flash templates are not supported, "%s" has no templates manage registered',
					get_class($this)
				));
			}

			$message = $this->getTemplatesManager()
				->load($message, ['type' => $type] + $context)
				->render()
			;
		}

		$this->session->getSegment('messages')->setFlashNow($type, $message);

		return $this;
	}
}
