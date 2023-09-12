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
        $images =  $response->filter('#maincontent > div.productCont div.breeze-gallery.vertical div.thumbnails a')->each(function($node) {
            return $node->attr('href');
        });
        $features =  $response->filter('#maincontent > div.productCont div.tabbs div.tables__items div:nth-child(1) div div.har div.har__row')->each(function($node) {
            return ['key' => $node->filter('div.har__title')->text(), 'value' => $node->filter('div.har__znach')->text()];
        });
        yield $this->item([
            'title' => $title,
            'images' => $images,
            'features' => $features,
        ]);
    }



    public function parse2(Response $response): Generator
    {
        $title =  $response->filter('#product-info h1.title')->text();
        $images =  $response->filter('#product-info > div.product-img-view > div.menu-view > div.side-menu > .slider > button > img')->each(function($node) {
            return str_replace('_png.webp', '.png', $node->attr('data-src'));
        });
        $_features =  $response->filter('#review-tabs #myTabContent ul li')->each(function($node) {
            return $node->text();
        });
        $features = array_chunk($_features, count($_features) / 2);

        yield $this->item([
            'title' => $title,
            'images' => $images,
            'features' => array_combine($features[0], $features[1]),
        ]);
    }
}
