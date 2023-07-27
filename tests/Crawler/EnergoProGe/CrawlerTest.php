<?php

namespace App\Tests\Crawler\EnergoProGe;

use App\Crawler\EnergoProGe\Crawler;
use PHPUnit\Framework\TestCase;

class CrawlerTest extends TestCase
{
    public function testSetters(): void
    {
        $city = 'ქუთაისი';
        $dateFrom = (new \DateTime('tomorrow'))->setTime(0, 0);
        $dateTo = (new \DateTime('tomorrow'))->setTime(23, 59);
        $dateInRange = (new \DateTime('tomorrow'))->setTime(22, 59);
        $dateOutRange = (new \DateTime())->setTime(0, 0);

        $crawler = new Crawler();
        $crawler->setCity($city);
        $crawler->setDateFrom(clone $dateFrom);
        $crawler->setDateTo(clone $dateTo);

        $this->assertEquals($city, $crawler->city);
        $this->assertEquals($dateFrom, $crawler->dateFrom, 'dateFrom');
        $this->assertEquals($dateTo, $crawler->dateTo, 'dateTo');

        $this->assertTrue($crawler->isDateSatisfy($dateInRange), 'dateInRange');
        $this->assertFalse($crawler->isDateSatisfy($dateOutRange), 'dateOutRange');
    }
}
