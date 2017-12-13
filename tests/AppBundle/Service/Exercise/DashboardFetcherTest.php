<?php

namespace Test\AppBundle\Service\Exercise;

use AppBundle\Entity\Exercise;
use AppBundle\Repository\ExerciseRepository;
use AppBundle\Service\DateTime\DateTimeFormatter;
use AppBundle\Service\Exercise\DashboardFetcher;

use PHPUnit\Framework\TestCase;

class DashboardFetcherTest extends TestCase
{
    protected $repo;

    protected $fetcher;

    protected $formatter;

    protected $today;

    protected $weekAgo;

    protected $twoWeeksAgo;

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

    public function testFetchResultsAreReturnedAsArrayWithThreeColumns()
    {
        $startDate = new \DateTime($this->today);

        $this->repo
            ->method('findBy')
            ->willReturn([]);

        $results = $this->fetcher->fetch($startDate);

        $this->assertInternalType('array', $results);

        $this->assertArrayHasKey($this->today, $results);
        $this->assertArrayHasKey($this->weekAgo, $results);
        $this->assertArrayHasKey($this->twoWeeksAgo, $results);
    }

    public function testFetchedRecordsAreDividedBetweenDates()
    {
        $record1 = $this->createMock(Exercise::class);
        $record2 = $this->createMock(Exercise::class);
        $record3 = $this->createMock(Exercise::class);
        $record4 = $this->createMock(Exercise::class);

        $record1->method('getDate')->willReturn(new \DateTime($this->today));
        $record2->method('getDate')->willReturn(new \DateTime($this->weekAgo));
        $record3->method('getDate')->willReturn(new \DateTime($this->twoWeeksAgo));
        $record4->method('getDate')->willReturn(new \DateTime($this->weekAgo));


        $this->repo
            ->method('findBy')
            ->willReturn([ $record1, $record2, $record3, $record4 ]);

        $results = $this->fetcher->fetch(new \DateTime());

        $this->assertContains($record1, $results[$this->today]);
        $this->assertContains($record3, $results[$this->twoWeeksAgo]);
        $this->assertContains($record2, $results[$this->weekAgo]);
        $this->assertContains($record2, $results[$this->weekAgo]);
    }

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

        $results = $this->fetcher->fetch(new \DateTime());

        $this->assertEquals($record2, $results[$this->today][0]);
        $this->assertEquals($record1, $results[$this->today][1]);
    }
}
