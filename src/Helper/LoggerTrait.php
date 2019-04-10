<?php

namespace App\Helper;

use Psr\Log\LoggerInterface;

// THE TRAIT
trait LoggerTrait
{

	// the setter dependencie to set logger
	/**
	 * @var LoggerInterface|null
	 */
	private $logger;

	/**
	 * @required
	 */
	public function setLogger(LoggerInterface $logger) 
	{
		$this->logger = $logger; 
	}

	// logInfo message method, 2para: the message (string) & the context (array)
	private function logInfo(string $message, array $context = [])
	{
		if ($this->logger) { 
			$this->logger->info($message, $context);
		}
	}

}