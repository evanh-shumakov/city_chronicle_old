<?php

namespace App\Tests\Unit\Crawler\EnergoProGe\Factory\Entity;

use App\Crawler\EnergoProGe\Factory\Entity\ContentFactory;
use PHPUnit\Framework\TestCase;

final class ContentFactoryTest extends TestCase
{
    public function testParses(): void
    {
        $factory = new ContentFactory();
        $origin = $this->getOrigin();
        $addresses = $factory->parseAddresses($origin)->toArray();
        $this->assertSame($this->getFirstAddress(), $addresses[0]->origin);
        $this->assertSame($this->getSecondAddress(), $addresses[1]->origin);
        $this->assertSame($this->getThirdAddress(), $addresses[2]->origin);
        $this->assertEquals($this->getStartDate(), $factory->parseStartDate($origin));
        $this->assertEquals($this->getEndDate(), $factory->parseEndDate($origin));
        $this->assertSame($this->getServiceCenter(), $factory->parseServiceCenter($origin));
    }

    private function getOrigin(): string
    {
        $firstAddress = $this->getFirstAddress();
        $secondAddress = $this->getSecondAddress();
        $thirdAddress = $this->getThirdAddress();
        $start = $this->getStartDate()->format('Y-m-d H:i');
        $end = $this->getEndDate()->format('Y-m-d H:i');
        $serviceCenter = $this->getServiceCenter();
        return <<<CONTENT
            ტიპი : გეგმიური
            რეგიონი : დასავლეთ-ცენტრალური რეგიონი
            სერვის ცენტრი : $serviceCenter
            აბონენტი:
            
            გათიშვის არეალი: $firstAddress, $secondAddress, $thirdAddress
            
            დასახელება: ქ/ს ავანგარდი ფიდ.ცგპ 10 გადართვები/სქემის ცვლილება
            შენიშვნა : ფიდ.ცგპ 10
            
            ჩაჭრილთა რაოდენობა : 1061
            ჩაჭრის თარიღი : $start
            აღდგენის თარიღი : $end
            CONTENT;
    }

    private function getFirstAddress(): string
    {
        return 'ქუთაისი/გუგუნავას';
    }

    private function getSecondAddress(): string
    {
        return 'ქუთაისი/ბუკიას';
    }

    private function getThirdAddress(): string
    {
        return 'ქუთაისი/ქვიტირი';
    }

    private function getServiceCenter(): string
    {
        return 'ქუთაისი';
    }

    private function getStartDate(): \DateTime
    {
        return (new \DateTime())
            ->setDate(2023,9, 14)
            ->setTime(17, 28);
    }

    private function getEndDate(): \DateTime
    {
        return (new \DateTime())
            ->setDate(2023,9, 14)
            ->setTime(19, 47);
    }
}
