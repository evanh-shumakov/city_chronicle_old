<?php

namespace App\Crawler\EnergoProGe\Factory\Entity;

use App\Crawler\EnergoProGe\Collection\ContentCollection;
use App\Crawler\EnergoProGe\Collection\OutageCollection;
use App\Crawler\EnergoProGe\Entity\Outage;

final readonly class OutageFactory
{
    public function makeOutages(ContentCollection $contents): OutageCollection
    {
        $outages = new OutageCollection();
        foreach ($contents as $content) {
            $outages->add(new Outage($content));
        }
        return $outages;
    }
}
