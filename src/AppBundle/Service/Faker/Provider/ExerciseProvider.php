<?php

namespace AppBundle\Service\Faker\Provider;

use Faker\Provider\Base;

/**
 * Custom Faker class to generate random exercise name
 *
 * @package AppBundle\Service\Faker\Provider
 */
class ExerciseProvider extends Base
{
    /**
     * Available exercises
     *
     * @var array
     */
    protected static $exercises = [
        'Leg Press',
        'Bench Press',
        'Tricep Pushdown',
    ];

    /**
     * Return a random exercise out of available list
     *
     * @return string
     */
    public static function exercise()
    {
        return self::randomElement(self::$exercises);
    }
}
