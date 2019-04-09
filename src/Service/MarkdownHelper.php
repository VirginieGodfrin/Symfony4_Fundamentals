<?php

namespace App\Service;
use Michelf\MarkdownInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Psr\Log\LoggerInterface;

// Never forget when you create a service class, the arguments to its constructor are autowired.
// That means that we can use any of the classes or interfaces from debug:autowiring as type-hints.
class MarkdownHelper
{
	// hello dependencies injection 
	private $cache;

	private $markdown;

	private $logger;

	private $isDebug;

	// use bind (If you find any argument named $markdownLogger , pass this service to it.)
	// isDebug or an API key - those should also be passed as constructor arguments.
	public function __construct(AdapterInterface $cache, MarkdownInterface $markdown, LoggerInterface $markdownLogger, bool $isDebug)
	{
		$this->cache = $cache;
		$this->markdown = $markdown;
		$this->logger = $markdownLogger;
		$this->isDebug = $isDebug;
	}

	public function parse(string $source): string
	{
		// dump($markdown);die;
        // dump($this->cache);die;

		// use the logger service
		if (stripos($source, 'bacon') !== false) { 
			$this->logger->info('They are talking about bacon again!');
		}

	    // skip caching entirely in debug
	    // kenel debug is false in prod
		if ($this->isDebug) {
			return $this->markdown->transform($source);
		}
		// create cache item
		$item = $this->cache->getItem('markdown_'.md5($source));

		// set item with sources transformed with markdown
		// save item in the cache
		if (!$item->isHit()) {
			$item->set($this->markdown->transform($source));
			$this->cache->save($item);
		}

		// return item who contain sources
		return $item->get();		
	}
}
