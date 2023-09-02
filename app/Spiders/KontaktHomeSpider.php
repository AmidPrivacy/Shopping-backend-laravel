<?php

namespace App\Spiders;

use Generator;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;
use RoachPHP\Extensions\LoggerExtension;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;

class KontaktHomeSpider extends BasicSpider
{
    public array $startUrls = [
        //
    ];

    public array $downloaderMiddleware = [
        RequestDeduplicationMiddleware::class,
    ];

    public array $spiderMiddleware = [
        //
    ];

    public array $itemProcessors = [
        //
    ];

    public array $extensions = [
        LoggerExtension::class,
        StatsCollectorExtension::class,
    ];

    public int $concurrency = 2;

    public int $requestDelay = 1;

    /**
     * @return Generator<ParseResult>
     */
    public function parse(Response $response): Generator
    {
        $title =  $response->filter('meta[property="og:title"]')->attr('content');
        $price = $response->filter('meta[property="product:price:amount"]')->attr('content');
        $images =  $response->filter('#maincontent > div.productCont div.breeze-gallery.vertical div.thumbnails a')->each(function($node) {
            return $node->attr('href');
        });
        $features =  $response->filter('#maincontent > div.productCont div.tabbs div.tables__items div:nth-child(1) div div.har div.har__row')->each(function($node) {
            return ['title' => $node->filter('div.har__title')->text(), 'value' => $node->filter('div.har__znach')->text()];
        });
        yield $this->item([
            'title' => $title,
            'price' => $price,
            'images' => $images,
            'features' => $features,
        ]);
    }
}
