<?php

namespace AppBundle\Service\DateTime;

/**
 * Service class to format DateTime objects
 *
 * @package AppBundle\Service\DateTime
 */
class DateTimeFormatter
{
    /**
     * Database date format
     *
     * @var string
     */
    const DB_FORMAT = 'Y-m-d';

    /**
     * Return string representation of the given date in DB format
     *
     * @param \DateTime $date
     * @return string
     */
    public function toDbFormat(\DateTime $date)
    {
        return $date->format(self::DB_FORMAT);
    }
}
