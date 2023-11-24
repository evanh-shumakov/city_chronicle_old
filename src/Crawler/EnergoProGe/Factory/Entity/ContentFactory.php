<?php

namespace App\Crawler\EnergoProGe\Factory\Entity;

use App\Crawler\EnergoProGe\Collection\AddressCollection;
use App\Crawler\EnergoProGe\Collection\ContentCollection;
use App\Crawler\EnergoProGe\Entity\Address;
use App\Crawler\EnergoProGe\Entity\Content;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
use Symfony\Component\Panther\DomCrawler\Crawler;

final class ContentFactory
{
    const CONTENT_SELECTOR =
        ".page-alert-info .page-alert-info-wrap-wrap .page-alert-info-wrap";

    /**
     * @param Crawler $elements
     * @return ContentCollection
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Exception Wrong node type for Crawler $elements
     */
    public function makeContentsWebDriver(Crawler $elements): ContentCollection
    {
        $origins = [];
        $contentDriver = WebDriverBy::cssSelector(self::CONTENT_SELECTOR);
        foreach ($elements as $element) {
            if (! $element instanceof WebDriverElement) {
                throw new \Exception("Wrong node type. Should be WebDriverElement.");
            }
            $origin = $element->findElement($contentDriver)->getText();
            $origins[] = $origin;
        }
        return $this->makeContents($origins);
    }

    /**
     * @param array<string> $origins
     * @return ContentCollection
     */
    public function makeContents(array $origins): ContentCollection
    {
        $contents = new ContentCollection();
        foreach ($origins as $origin) {
            $content = $this->makeContent($origin);
            $contents->add($content);
        }
        return $contents;
    }

    public function makeContent(string $origin): Content
    {
        return new Content(
            $this->parseAddresses($origin),
            $this->parseStartDate($origin),
            $this->parseEndDate($origin),
            $this->parseServiceCenter($origin),
            $origin
        );
    }

    public function parseAddresses(string $origin): AddressCollection
    {
        $startIndicator = "გათიშვის არეალი:";
        $endIndicator = "დასახელება:";
        $right = explode($startIndicator, $origin)[1];
        $left = explode($endIndicator, $right)[0];
        $addresses = explode(",", $left);
        $addressCollection = new AddressCollection();
        foreach ($addresses as $address) {
            $addressCollection->add(new Address(trim($address)));
        }
        return $addressCollection;
    }

    public function parseStartDate(string $origin): ?\DateTime
    {
        $startIndicator = "ჩაჭრის თარიღი :";
        $endIndicator = "აღდგენის თარიღი :";
        $right = explode($startIndicator, $origin)[1];
        $dateTime = trim(explode($endIndicator, $right)[0]);
        $dateTime = \DateTime::createFromFormat("Y-m-d H:i", $dateTime);
        if (! $dateTime) {
            return null;
        }
        return $dateTime;
    }

    public function parseEndDate(string $origin): ?\DateTime
    {
        $startIndicator = "აღდგენის თარიღი :";
        $dateStringLength = 16;
        $right = trim(explode($startIndicator, $origin)[1]);
        $dateTime = substr($right, 0, $dateStringLength);
        $dateTime = \DateTime::createFromFormat("Y-m-d H:i", $dateTime);
        if (! $dateTime) {
            return null;
        }
        return $dateTime;
    }

    public function parseServiceCenter(string $origin): string
    {
        $startIndicator = 'სერვის ცენტრი :';
        $endIndicator = 'აბონენტი';
        $right = explode($startIndicator, $origin)[1];
        return trim(explode($endIndicator, $right)[0]);
    }
}
