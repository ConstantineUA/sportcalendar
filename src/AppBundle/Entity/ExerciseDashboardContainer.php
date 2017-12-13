<?php

namespace AppBundle\Entity;

/**
 * Container class to hold excercises for dashboard page
 */
class ExerciseDashboardContainer
{
    /**
     * Start date for dashboard
     *
     * @var \DateTime
     */
    protected $startDate;

    /**
     * List of excercises for start date
     *
     * @var array
     */
    protected $startDateExercises = [];

    /**
     * List of excercises for a week before
     *
     * @var array
     */
    protected $weekAgoExercises = [];

    /**
     * List of excercises for 2 weeks before
     *
     * @var array
     */
    protected $twoWeeksAgoExercises = [];

    /**
     * Set start date
     *
     * @param \DateTime $startDate
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = clone $startDate;
        $this->startDate->setTime(0, 0, 0);
    }

    /**
     * Check excercise date and add it to corresponding array
     *
     * @param Exercise $exercise
     * @throws \RuntimeException
     */
    public function addExercise(Exercise $exercise)
    {
        $startDate = clone $this->startDate;

        $date = clone $exercise->getDate();
        $date->setTime(0, 0, 0);

        if ($date >= $startDate) {
            $this->addExerciseForStartDate($exercise);

            return;
        }

        $startDate->modify('-1 week');

        if ($date >= $startDate) {
            $this->addExerciseForWeekAgo($exercise);

            return;
        }

        $startDate->modify('-1 week');

        if ($date >= $startDate) {
            $this->addExerciseForTwoWeeksAgo($exercise);

            return;
        }

        throw new \RuntimeException('An attempt to add new exercise outside of date range ' . $date->format('Y-m-d'));
    }

    /**
     * Return list of excercises for the start date
     *
     * @return array
     */
    public function getExercisesForStartDate()
    {
        return $this->startDateExercises;
    }

    /**
     * Return list of excercises for a week before
     *
     * @return array
     */
    public function getExercisesForWeekAgo()
    {
        return $this->weekAgoExercises;
    }

    /**
     * Return list of excercises for 2 weeks before
     *
     * @return array
     */
    public function getExercisesForTwoWeeksAgo()
    {
        return $this->twoWeeksAgoExercises;
    }

    /**
     * Add excercise for start date list
     *
     * @param Exercise $exercise
     */
    protected function addExerciseForStartDate(Exercise $exercise)
    {
        $this->startDateExercises[] = $exercise;
    }

    /**
     * Add excercise for a week before list
     *
     * @param Exercise $exercise
     */
    protected function addExerciseForWeekAgo(Exercise $exercise)
    {
        $this->weekAgoExercises[] = $exercise;
    }

    /**
     * Add excercise for 2 weeks before list
     *
     * @param Exercise $exercise
     */
    protected function addExerciseForTwoWeeksAgo(Exercise $exercise)
    {
        $this->twoWeeksAgoExercises[] = $exercise;
    }
}
