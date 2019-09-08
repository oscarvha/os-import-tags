<?php


namespace OsImportTags\Util;


use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class Scrapping
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var mixed
     */
    private $data;

    /**
     * Scrapping constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->client = new Client();
        $this->getDataByUrl($url);
    }

    /**
     * @param string $url
     */
    private function getDataByUrl(string $url) : void
    {
       $this->data = $this->client->request('GET',$url);
    }

    /**
     * @return string
     */
    public function getH1() : string
    {
        /** @var $node Crawler */
      $h1 = $this->data->filter('h1')->each(function ($node)
        {

            return $node->text();
        });

       return $h1[0];

    }
}