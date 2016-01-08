<?php

namespace rsspipestests\sections;

use rsspipes\rss\Feed;
use rsspipes\rss\Item;
use rsspipes\sections\Page;

class PageTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $body = file_get_contents(__DIR__ . '/sample.html');
        
        $section = new Page();
        $section->url = 'http://urmaul.com/';
        $section->items = '.mainContent article';
        $section->title = 'header > h2 > a';
        $section->link = 'header > h2 > a';
        $section->description = 'div > p:not(:contains("Read full post"))';//:not(:contains("Read full post"))
        
        $items = $section->parseItems($body);
        
        $this->assertEquals('Imagick resize filters comparison', $items[0]->title);
        $this->assertEquals('http://urmaul.com/blog/imagick-filters-comparison', $items[0]->link);
        $this->assertEquals('So you want to shrink images with php and imagemagick. Here\'s samples of all filters so you can select the one you like most.', $items[0]->description);
        
        $this->assertEquals('Nicolas Cage as default avatar', $items[1]->title);
        $this->assertEquals('http://urmaul.com/blog/gravacage', $items[1]->link);
        $this->assertEquals('No one asked me about this for months. And now it\'s done. Gravacage has it\'s own <a href="https://github.com/urmaul/gravacage">documented php library</a> and <a href="http://gravacage.urmaul.com/">site</a>.', $items[1]->description);
        
        $this->assertEquals('How to attach composer to Yii project', $items[2]->title);
        $this->assertEquals('http://urmaul.com/blog/how-to-attach-composer-to-yii-project', $items[2]->link);
        $this->assertEquals(null, $items[2]->description);
        
        $this->assertEquals('Why composer matters', $items[3]->title);
        $this->assertEquals('http://urmaul.com/blog/why-composer-matters', $items[3]->link);
        $this->assertEquals('I\'ve heard about composer long before I started using it. I couldn\'t understand why it\'s so much cooler than downloading dependencies manually. I couldn\'t understand why it\'s worth running <code>composer install</code> after every code fetch.', $items[3]->description);
    }
}
