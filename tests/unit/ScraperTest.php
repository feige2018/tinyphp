<?php

namespace tests\unit;

use tests\BaseTest;
use React\Http\Browser;
use React\Filesystem\Filesystem;
use React\EventLoop\Factory as EventFactory;
use app\service\Scraper;

class ScraperTest extends BaseTest
{
	/**
	 * @test
	 */
	public function demo()
	{
        $loop = EventFactory::create();
        $client = new Browser($loop);
        $filesystem = Filesystem::creeate($loop);
        $directory = __DIR__ . '/down';

        $scraper = new Scraper($client, $filesystem, $directory);
        $scraper->scrape([
            'https://www.baidu.com/',
            'https://www.google.com/',
        ]);
        $loop->run();

		$this->assertTrue(true);
	}
}
