<?php

namespace tests\AppBundle\Entity;

use PHPUnit\Framework\TestCase;
use AppBundle\Entity\ExerciseDashboardContainer;
use AppBundle\Entity\Exercise;

/**
 * Test cases for ExerciseDashboardContainer class
 */
class ExerciseDashboardContainerTest extends TestCase
{
    /**
     * SUT
     *
     * @var ExerciseDashboardContainer
     */
    protected $container;

    /**
     * String date used in tests as today
     *
     * @var string
     */
    protected $today;

    /**
     * String date used in tests as a week ago
     *
     * @var string
     */
    protected $weekAgo;

    /**
     * String date used in tests as 2 weeks ago
     *
     * @var string
     */
    protected $twoWeeksAgo;

    /**
     * Initialize SUT
     */
    public function setUp()
    {
        $this->today = '2017-09-24';
        $this->weekAgo = '2017-09-17';
        $this->twoWeeksAgo = '2017-09-10';

        $this->container = new ExerciseDashboardContainer();
        $this->container->setStartDate(new \DateTime($this->today));
    }

    /**
     * Check that excercise not in range is ingored with exception
     */
    public function testAddExerciseThrowsExceptionForExerciseNotInRange()
    {
        $this->expectException(\RuntimeException::class);

        $excercise = $this->createMock(Exercise::class);
        $excercise->method('getDate')->willReturn(new \DateTime('2017-01-01'));

        $this->container->addExercise($excercise);
    }

    /**
     * Check that excercises are properly divided
     */
    public function testAddExerciseAddsExerciseToCorrespondingList()
    {
        $excercise1 = $this->createMock(Exercise::class);
        $excercise2 = $this->createMock(Exercise::class);
        $excercise3 = $this->createMock(Exercise::class);
        $excercise4 = $this->createMock(Exercise::class);

        $excercise1->method('getDate')->willReturn(new \DateTime($this->today));
        $excercise2->method('getDate')->willReturn(new \DateTime($this->weekAgo));
        $excercise3->method('getDate')->willReturn(new \DateTime($this->twoWeeksAgo));
        $excercise4->method('getDate')->willReturn(new \DateTime($this->weekAgo));


        $this->container->addExercise($excercise1);
        $this->container->addExercise($excercise2);
        $this->container->addExercise($excercise3);
        $this->container->addExercise($excercise4);

        $this->assertContains($excercise1, $this->container->getExercisesForStartDate());
        $this->assertContains($excercise2, $this->container->getExercisesForWeekAgo());
        $this->assertContains($excercise3, $this->container->getExercisesForTwoWeeksAgo());
        $this->assertContains($excercise4, $this->container->getExercisesForWeekAgo());
    }
}
