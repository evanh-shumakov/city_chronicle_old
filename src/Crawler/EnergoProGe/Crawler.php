<?php

namespace App\Crawler\EnergoProGe;

use App\Crawler\EnergoProGe\Collection\AddressCollection;
use App\Crawler\EnergoProGe\Collection\OutageCollection;
use App\Crawler\EnergoProGe\Entity\Address;
use App\Crawler\EnergoProGe\Entity\Outage;
use Symfony\Component\Panther\Client;
use Facebook\WebDriver\WebDriverBy;

class Crawler
{
    const OUTAGE_LIST_URL = "https://my.energo-pro.ge/ow/#/disconns";

    const CITY_SELECTOR = ".page-alert .page-alert-text-title b";

    const CONTENT_SELECTOR = ".page-alert-info .page-alert-info-wrap-wrap .page-alert-info-wrap";

    const BUTTON_SELECTOR = ".page-alert-wrap .page-alert-togglers-wrap";
    const KUTAISI = 'ქუთაისი';

    private string $city;

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function fetchOutageList(): OutageCollection
    {
        $client = Client::createChromeClient(); // arguments:['--window-size=1280,800', ] // Set the window size
        $client->request('GET', self::OUTAGE_LIST_URL);
        $crawler = $client->getCrawler();
        $this->clickButtonsToLoadContent($client);
        $elements = $crawler->filter(".page-alert-wrap");
        $outages = new OutageCollection();
        foreach ($elements as $element) {
            $outage = $this->composeOutage($element);
            if (! is_null($outage)) {
                $outages->add($outage);
            }
        }
        return $outages;
    }

    private function clickButtonsToLoadContent(Client $client): void
    {
        $crawler = $client->getCrawler();
        $buttons = $crawler->filter(self::BUTTON_SELECTOR);

        foreach ($buttons as $button) {
            $script = 'arguments[0].click();';
            $client->executeScript($script, [$button]);
        }
    }

    private function composeOutage(mixed $element): ?Outage
    {
        $cityDriver = WebDriverBy::cssSelector(self::CITY_SELECTOR);
        $city = trim($element->findElement($cityDriver)->getText());

        if (isset($this->city) && $city !== $this->city) {
            return null;
        }

        $contentDriver = WebDriverBy::cssSelector(self::CONTENT_SELECTOR);
        $content = $element->findElement($contentDriver)->getText();
        $addresses = $this->parseAddresses($content);
        $dateStart = $this->parseStartDateTime($content);
        $dateEnd = $this->parseEndDateTime($content);
        return new Outage($dateStart, $dateEnd, $city, $addresses);
    }

    private function parseAddresses(string $content): AddressCollection
    {
        $startIndicator = "გათიშვის არეალი:";
        $endIndicator = "დასახელება:";
        $right = explode($startIndicator, $content)[1];
        $left = explode($endIndicator, $right)[0];
        $addresses = explode(",", $left);
        $addressCollection = new AddressCollection();
        foreach ($addresses as $address) {
            $addressCollection->add(new Address(trim($address)));
        }
        return $addressCollection;
    }

    private function parseStartDateTime(string $content): \DateTime
    {
        $startIndicator = "ჩაჭრის თარიღი :";
        $endIndicator = "აღდგენის თარიღი :";
        $right = explode($startIndicator, $content)[1];
        $dateTime = trim(explode($endIndicator, $right)[0]);
        return \DateTime::createFromFormat("Y-m-d H:i", $dateTime);
    }

    private function parseEndDateTime(string $content): \DateTime
    {
        $startIndicator = "აღდგენის თარიღი :";
        $dateStringLength = 16;
        $right = trim(explode($startIndicator, $content)[1]);
        $dateTime = substr($right, 0, $dateStringLength);
        return \DateTime::createFromFormat("Y-m-d H:i", $dateTime);
    }
}
