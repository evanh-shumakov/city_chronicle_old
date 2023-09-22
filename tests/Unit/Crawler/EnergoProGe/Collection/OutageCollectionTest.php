<?php

namespace App\Tests\Unit\Crawler\EnergoProGe\Collection;

use App\Crawler\EnergoProGe\Collection\AddressCollection;
use App\Crawler\EnergoProGe\Collection\OutageCollection;
use App\Crawler\EnergoProGe\Entity\Content;
use App\Crawler\EnergoProGe\Entity\Outage;
use PHPUnit\Framework\TestCase;

final class OutageCollectionTest extends TestCase
{
    public function testGettersBy(): void
    {
        $outageFoo = new Outage(new Content(
            new AddressCollection(),
            new \DateTime("2023/10/10"),
            new \DateTime("2023/10/10"),
            'serviceFoo',
            'originalFoo'
        ));
        $outageBar = new Outage(new Content(
            new AddressCollection(),
            new \DateTime("2023/11/6"),
            new \DateTime("2023/11/11"),
            'myService',
            'originalBar'
        ));
        $outageBaz = new Outage(new Content(
            new AddressCollection(),
            new \DateTime("2023/11/6"),
            new \DateTime("2023/11/7"),
            'myService',
            'originalBaz'
        ));

        $collection = new OutageCollection();
        $collection->add($outageFoo);
        $collection->add($outageBar);
        $collection->add($outageBaz);

        $this->assertSame($outageFoo, $collection->getByServiceCentre('serviceFoo')->current());
        $this->assertSame(2, $collection->getByServiceCentre('myService')->count());
        $this->assertSame($outageFoo, $collection->getByDate(new \DateTime("2023/10/10"))->current());
        $this->assertSame(2, $collection->getByDate(new \DateTime("2023/11/6"))->count());
    }
}
