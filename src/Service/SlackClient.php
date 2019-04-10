<?php

namespace App\Service;

use Nexy\Slack\Client;
use Psr\Log\LoggerInterface;
use App\Helper\LoggerTrait;

// Slack client services
class SlackClient
{
	// THE TRAIT
	use LoggerTrait;

	private $slack;

	public function __construct(Client $slack)
	{
		$this->slack= $slack;
	}

	// Before create THE TRAIT, discover the setter injection, we use it for optionnal dependencie like loger !
	// The propriety with annotaion for phpStrom autocomplete
	// /**
	//  * @var LoggerInterface|null
	//  */
	// private $logger;

	// The setter
	// Symfony only autowires the__construct() method
	// Use the magic required annotation and
	// symfony will call that method before giving us the object
	// And thanks to autowire the logger service is passing to the argument. Fun Fun !
	// /**
	//  * @required
	//  */
	//  public function setLogger(LoggerInterface $logger)
	//  {
	// 	 $this->logger = $logger;
	//  }

	// the methode sendMessage that send message , two para: $from & $message
	public function sendMessage(string $from, string $message)
	{
		// Call the logInfo message method from LoggerTrait
		$this->logInfo(
			// 1°para: the string (message)
			'Beaming a message to Slack!',
			// 2°para: the array (context)
			[ 'message' => $message ]
		);

		// use it like dependencie injection 
		// if ($this->logger) {
		// 	$this->logger->info('Beaming a message to Slack!');
		// }

		$message = $this->slack->createMessage() 
			->from($from)
			->withIcon(':ghost:') 
			->setText($message);

		$this->slack->sendMessage($message);

	}
}
