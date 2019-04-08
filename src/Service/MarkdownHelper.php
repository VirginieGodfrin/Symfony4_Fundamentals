<?php

namespace App\Service;
use Michelf\MarkdownInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

// Never forget when you create a service class, the arguments to its constructor are autowired.
// That means that we can use any of the classes or interfaces from debug:autowiring as type-hints.
class MarkdownHelper
{
	// hello dependencies injection 
	private $cache;

	private $markdown;

	public function __construct(AdapterInterface $cache, MarkdownInterface $markdown)
	{
		$this->cache = $cache;
		$this->markdown = $markdown;
	}

	public function parse(string $source): string{

	    $item = $this->cache->getItem('markdown_'.md5($articleContent));

		if (!$item->isHit()) {

			$item->set($this->markdown->transform($articleContent));
			$this->cache->save($item);
		}
		$articleContent = $item->get();		
	}
}
