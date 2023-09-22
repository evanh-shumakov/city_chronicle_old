<?php

namespace App\Tests\Unit\Crawler\EnergoProGe\Collection;

use App\Crawler\EnergoProGe\Collection\AddressCollection;
use App\Crawler\EnergoProGe\Entity\Address;
use PHPUnit\Framework\TestCase;

final class AddressCollectionTest extends TestCase
{
    public function testImplode(): void
    {
        $separator = '-';
        $foo = 'foo';
        $bar = 'Bar';
        $baz = 'baZ';
        $imploded = $foo . $separator . $bar . $separator . $baz;
        $collection = new AddressCollection();
        $collection->add(new Address($foo));
        $collection->add(new Address($bar));
        $collection->add(new Address($baz));

        $this->assertSame($imploded, $collection->implode($separator));
    }
}

