<?php

namespace app\service;

use React\Http\Browser;
use React\Filesystem\Filesystem;
use React\Filesystem\FilesystemInterface;
use React\EventLoop\Factory as EventFactory;
use Psr\Http\Message\ResponseInterface;

/**
 * 一个简单的爬虫类
 */
class Scraper
{
    private $client;
    private $directory;
    private $filesystem;

    public function __construct(Browser $client, FilesystemInterface $filesystem = null, string $directory = "")
    {
        $this->client = $client;
        $this->directory = $directory;
        $this->filesystem = $filesystem;
    }

    public function scrape(array $urls)
    {
        foreach ($urls as $url) {
            $this->client->get($url)->then(
                function (ResponseInterface $response) {
                    $body = (string) $response->getBody();
                    $this->processResponse($body);
                });
        }
    }

    private function processResponse(string $html)
    {
        $fileName = $this->directory."/".uniqid().".txt";

        if ($this->filesystem) {

            $this->filesystem->file($fileName)->putContents($html);

            // 提取图片地址
            $imageUrls = [
                // ...
            ];

            foreach ($imageUrls as $url) {
                $this->client->get($url)->then(
                    function (ResponseInterface $response) {
                        $body = (string) $response->getBody();
                        $fileName = $this->directory."/".uniqid().".png";
                        $this->filesystem->file($fileName)->putContents($body);
                    });
            }
        }
    }
}
