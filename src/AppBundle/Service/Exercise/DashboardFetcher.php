<?php

namespace AppBundle\Service\Exercise;

use AppBundle\Repository\ExerciseRepository;

/**
 * Service class to prepare exercise records to be displayed on the dashboard
 *
 * @package AppBundle\Service\Exercise
 */
class DashboardFetcher
{
    /**
     * @var ExerciseRepository
     */
    protected $repo;

    /**
     * Constructor, inject dependencies
     *
     * @param ExerciseRepository $repo
     */
    public function __construct(ExerciseRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Fetch exercise records for the given date
     * and the same date one and two weeks before
     * and return them as array grouped by date
     *
     * @param \DateTime $startDate
     * @return array
     */
    public function fetch(\DateTime $startDate)
    {
        $weekBefore = (clone $startDate)->modify('-1 week');
        $twoWeeksBefore = (clone $startDate)->modify('-2 weeks');

        $results = [
            $twoWeeksBefore->format('Y-m-d') => [],
            $weekBefore->format('Y-m-d') => [],
            $startDate->format('Y-m-d') => [],
        ];

        $records = $this->repo->findBy([
            'date' => array_keys($results),
        ]);

        usort($records, function ($exerciseA, $exerciseB) {
            return $exerciseA->getDescription() <=> $exerciseB->getDescription();
        });

        foreach ($records as $record) {
            $results[$record->getDate()->format('Y-m-d')][] = $record;
        }

        return $results;
    }
}
