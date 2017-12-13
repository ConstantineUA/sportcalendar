<?php

namespace Test\AppBundle\Service\Exercise;

use AppBundle\Entity\Exercise;
use AppBundle\Repository\ExerciseRepository;
use AppBundle\Service\DateTime\DateTimeFormatter;
use AppBundle\Service\Exercise\DashboardFetcher;

use PHPUnit\Framework\TestCase;
use AppBundle\Entity\ExerciseDashboardContainer;

/**
 * Unit tests for DashboardFetcher service
 */
class DashboardFetcherTest extends TestCase
{
    /**
     * Mock for ExerciseRepository
     *
     * @var ExerciseRepository
     */
    protected $repo;

    /**
     * SUT
     *
     * @var DashboardFetcher
     */
    protected $fetcher;

    /**
     * Mock for DateTimeFormatter
     *
     * @var DateTimeFormatter
     */
    protected $formatter;

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
     * Initialize all mock and sut
     */
    public function setUp()
    {
        $this->repo = $this->createMock(ExerciseRepository::class);

        $this->formatter = $this->createMock(DateTimeFormatter::class);

        $this->formatter
            ->method('toDbFormat')
            ->will($this->returnCallback(function ($value) {
                return $value->format('Y-m-d');
            }));

        $this->fetcher = new DashboardFetcher($this->repo, $this->formatter);

        $this->today = '2017-09-24';
        $this->weekAgo = '2017-09-17';
        $this->twoWeeksAgo = '2017-09-10';
    }

    /**
     * Test that repository is asked for exercises in the correct daterange
     */
    public function testRepositoryIsAskedCorrectDates()
    {
        $startDate = new \DateTime($this->today);

        $expectedParams = [
            'date' => [
                $this->twoWeeksAgo, $this->weekAgo, $this->today,
            ],
        ];

        $this->repo
            ->expects($this->once())
            ->method('findBy')
            ->with($expectedParams)
            ->willReturn([]);


        $this->fetcher->fetch($startDate);
    }

    /**
     * Test format of returned results
     */
    public function testFetchResultsAreReturnedAsDashboardContainer()
    {
        $startDate = new \DateTime($this->today);

        $this->repo
            ->method('findBy')
            ->willReturn([]);

        $results = $this->fetcher->fetch($startDate);

        $this->assertInstanceOf(ExerciseDashboardContainer::class, $results);
    }

    /**
     * Test that exercises are properly sorted by description
     */
    public function testFetchedRecordsAreSorted()
    {
        $record1 = $this->createMock(Exercise::class);
        $record2 = $this->createMock(Exercise::class);

        $record1->method('getDate')->willReturn(new \DateTime($this->today));
        $record2->method('getDate')->willReturn(new \DateTime($this->today));

        $record1->method('getDescription')->willReturn('Exercise Z');
        $record2->method('getDescription')->willReturn('Exercise A');

        $this->repo
            ->method('findBy')
            ->willReturn([$record1, $record2]);

        $fetcher = $this->getMockBuilder(DashboardFetcher::class)
            ->setMethods([ 'getDashboardContainer' ])
            ->setConstructorArgs([ $this->repo, $this->formatter ])
            ->getMock();

        $container = $this->createMock(ExerciseDashboardContainer::class);

        $container
            ->expects($this->exactly(2))
            ->method('addExercise')
            ->withConsecutive([ $record2 ], [ $record1 ]);

        $fetcher
            ->method('getDashboardContainer')
            ->willReturn($container);

        $fetcher->fetch(new \DateTime($this->today));
    }
}
