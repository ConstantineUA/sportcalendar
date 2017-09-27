<?php

namespace AppBundle\Service\Exercise;

use AppBundle\Repository\ExerciseRepository;
use AppBundle\Service\DateTime\DateTimeFormatter;

/**
 * Service class to prepare exercise records to be displayed on the dashboard
 *
 * @package AppBundle\Service\Exercise
 */
class DashboardFetcher
{
    /**
     * Exercise entity repository
     *
     * @var ExerciseRepository
     */
    protected $repo;

    /**
     * Helper class to format DateTime objects
     *
     * @var DateTimeFormatter
     */
    protected $formatter;

    /**
     * Constructor, inject dependencies
     *
     * @param ExerciseRepository $repo
     * @param DateTimeFormatter $formatter
     */
    public function __construct(ExerciseRepository $repo, DateTimeFormatter $formatter)
    {
        $this->repo = $repo;
        $this->formatter = $formatter;
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
            $this->formatter->toDbFormat($twoWeeksBefore) => [],
            $this->formatter->toDbFormat($weekBefore) => [],
            $this->formatter->toDbFormat($startDate) => [],
        ];

        // I'd prefer to have a custom method in the repository to run this query
        // along with the logic to prepare dates but the task description
        // suggested to implement it without repository methods
        $records = $this->repo->findByDate(array_keys($results));

        usort($records, function ($exerciseA, $exerciseB) {
            return strnatcasecmp($exerciseA->getDescription(), $exerciseB->getDescription());
        });

        foreach ($records as $record) {
            $results[$this->formatter->toDbFormat($record->getDate())][] = $record;
        }

        return $results;
    }
}
