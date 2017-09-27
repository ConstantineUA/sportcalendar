<?php

namespace Tests\AppBundle\Service\DateTime;

use AppBundle\Service\DateTime\DateTimeFormatter;

use PHPUnit\Framework\TestCase;

class DateTimeFormatterTest extends TestCase
{
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new DateTimeFormatter();
    }

    public function testToDbFormat()
    {
        $dateStr = '2017-09-25';

        $date = \DateTime::createFromFormat('Y-m-d', $dateStr);

        $this->assertEquals($dateStr, $this->formatter->toDbFormat($date));
    }

}
