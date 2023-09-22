<?php

namespace App\Crawler\EnergoProGe;

use App\Crawler\EnergoProGe\Collection\ContentCollection;
use App\Crawler\EnergoProGe\Collection\OutageCollection;
use App\Crawler\EnergoProGe\Factory\Entity\ContentFactory;
use App\Crawler\EnergoProGe\Factory\Entity\Entity\OutageFactory;
use Symfony\Component\Panther\Client;

final readonly class Crawler
{
    const OUTAGE_LIST_URL = "https://my.energo-pro.ge/ow/#/disconns";

    const SERVICE_CENTER_SELECTOR = ".page-alert .page-alert-text-title b";

    const BUTTON_SELECTOR = ".page-alert-wrap .page-alert-togglers-wrap";

    public OutageFactory $outageFactory;

    public function __construct()
    {
        $this->outageFactory = new OutageFactory();
    }

    public function fetchOutages(): OutageCollection
    {
        $content = $this->fetchOutageEntryAsStrings();
        return $this->outageFactory->makeOutages($content);
    }

    public function fetchOutageEntryAsStrings(): ContentCollection
    {
        $client = Client::createChromeClient(); // arguments:['--window-size=1280,800', ] // Set the window size
        $client->request('GET', self::OUTAGE_LIST_URL);
        $crawler = $client->getCrawler();
        $this->clickButtonsToLoadContent($client);
        $elements = $crawler->filter(".page-alert-wrap");
        $factory = new ContentFactory();
        return $factory->makeContentsWebDriver($elements);
    }

    public function clickButtonsToLoadContent(Client $client): void
    {
        $crawler = $client->getCrawler();
        $buttons = $crawler->filter(self::BUTTON_SELECTOR);

        foreach ($buttons as $button) {
            $script = 'arguments[0].click();';
            $client->executeScript($script, [$button]);
        }
    }
}
