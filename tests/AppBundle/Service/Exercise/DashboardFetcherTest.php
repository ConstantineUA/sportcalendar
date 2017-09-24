<?php

namespace Test\AppBundle\Service\Exercise;

use AppBundle\Entity\Exercise;
use AppBundle\Repository\ExerciseRepository;
use AppBundle\Service\Exercise\DashboardFetcher;

use PHPUnit\Framework\TestCase;

class DashboardFetcherTest extends TestCase
{
    protected $repo;

    protected $fetcher;

    protected $today;

    protected $weekAgo;

    protected $twoWeeksAgo;

    public function setUp()
    {
        $this->repo = $this->createMock(ExerciseRepository::class);

        $this->fetcher = new DashboardFetcher($this->repo);

        $this->today = '2017-09-24';
        $this->weekAgo = '2017-09-17';
        $this->twoWeeksAgo = '2017-09-10';
    }

    public function testRepositoryIsAskedCorrectDates()
    {
        $startDate = new \DateTime($this->today);

        $expectedParams = [
            'date' => [ $this->today, $this->weekAgo, $this->twoWeeksAgo ],
        ];

        $this->repo
            ->expects($this->once())
            ->method('findBy')
            ->with($this->equalTo($expectedParams, 0, 10, true))
            ->will($this->returnValue([]));


        $this->fetcher->fetch($startDate);
    }

    public function testFetchResultsAreReturnedAsArrayWithThreeColumns()
    {
        $startDate = new \DateTime($this->today);

        $this->repo
            ->method('findBy')
            ->will($this->returnValue([]));

        $results = $this->fetcher->fetch($startDate);

        $this->assertInternalType('array', $results);

        $this->assertArrayHasKey($this->today, $results);
        $this->assertArrayHasKey($this->weekAgo, $results);
        $this->assertArrayHasKey($this->twoWeeksAgo, $results);
    }

    public function testFetchedRecordsAreOrderedBetweenDates()
    {
        $record1 = $this->createMock(Exercise::class);
        $record2 = $this->createMock(Exercise::class);
        $record3 = $this->createMock(Exercise::class);
        $record4 = $this->createMock(Exercise::class);

        $record1->method('getDate')->will($this->returnValue(new \DateTime($this->today)));
        $record2->method('getDate')->will($this->returnValue(new \DateTime($this->weekAgo)));
        $record3->method('getDate')->will($this->returnValue(new \DateTime($this->twoWeeksAgo)));
        $record4->method('getDate')->will($this->returnValue(new \DateTime($this->weekAgo)));


        $this->repo
            ->method('findBy')
            ->will($this->returnValue([ $record1, $record2, $record3, $record4 ]));

        $results = $this->fetcher->fetch(new \DateTime());

        $this->assertContains($record1, $results[$this->today]);
        $this->assertContains($record3, $results[$this->twoWeeksAgo]);
        $this->assertContains($record2, $results[$this->weekAgo]);
        $this->assertContains($record2, $results[$this->weekAgo]);
    }
}
