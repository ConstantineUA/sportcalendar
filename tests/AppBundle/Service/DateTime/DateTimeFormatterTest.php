<?php

namespace Tests\AppBundle\Service\DateTime;

use AppBundle\Service\DateTime\DateTimeFormatter;

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for DateTimeFormatter
 */
class DateTimeFormatterTest extends TestCase
{
    /**
     * SUT
     *
     * @var DateTimeFormatter
     */
    protected $formatter;

    /**
     * Initial setup
     */
    public function setUp()
    {
        $this->formatter = new DateTimeFormatter();
    }

    /**
     * Check convertion to DB format
     */
    public function testToDbFormat()
    {
        $dateStr = '2017-09-25';

        $date = \DateTime::createFromFormat('Y-m-d', $dateStr);

        $this->assertEquals($dateStr, $this->formatter->toDbFormat($date));
    }
}
