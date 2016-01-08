<?php

namespace rsspipes\sections;

use rsspipes\rss\Feed;
use rsspipes\rss\Item;
use Symfony\Component\DomCrawler\Crawler;
use urmaul\url\Url;

class Page extends Section
{
    public $url;
    
    public $items;
    
    public $title;
    public $link;
    public $description;
    
    /**
     * @param Feed $feed
     */
    public function processFeed($feed)
    {
        $body = \HttpClient::from()->get($this->url);
        $feed->items = array_merge($feed->items, $this->parseItems($body, $feed));
    }
    
    /**
     * 
     * @param string $body
     * @return Item[]
     */
    public function parseItems($body)
    {
        $crawler = new Crawler();
        $crawler->addContent($body);
        
        $nodes = $this->items ? $crawler->filter($this->items) : $crawler;
        
        $items = [];
        $nodes->each(function (Crawler $block) use (&$items) {
            $items[] = $this->parseItem($block);
        });
        return $items;
    }
    
    /**
     * 
     * @param Crawler $block
     * @return Item
     */
    public function parseItem(Crawler $block)
    {
        $item = new Item();
        if ($this->title)
            $item->title = $block->filter($this->title)->text();
        if ($this->link) {
            $node = $block->filter($this->link);
            $href = $node->attr('href');
            $item->link = Url::from($href)->absolute($this->url);
        }
        if ($this->description) {
            $node = $block->filter($this->description);
            if ($node->count()) {
                $item->description = $node->html();
            }
        }
        
        return $item;
    }
}
