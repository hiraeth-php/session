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
	protected function flash($type, $message, array $context = []): self
	{
		if (!$this instanceof ManagedInterface) {
			throw new RuntimeException(sprintf(
				'Flash is not supported, "%s" is not implemented by "%s"',
				ManagedInterface::class,
				$this::class
			));
		}

		if (!$this->getSessionManager()) {
			throw new RuntimeException(sprintf(
				'Flash is not supported, "%s" has no session manage registered',
				$this::class
			));
		}

		if ($message[0] == '@') {
			if (!$this instanceof Templates\ManagedInterface) {
				throw new RuntimeException(sprintf(
					'Flash templates are not supported, "%s" is not implemented by "%s"',
					Templates\ManagedInterface::class,
					$this::class
				));
			}

			if (!$this->getTemplatesManager()) {
				throw new RuntimeException(sprintf(
					'Flash templates are not supported, "%s" has no templates manage registered',
					$this::class
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
