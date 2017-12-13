<?php

namespace AppBundle\Controller;

use AppBundle\Service\Exercise\DashboardFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    /**
     * Display list of done exercises for today, a week ago and 2 weeks ago
     *
     * @Route("/calendar", name="app_dashboard_calendar")
     *
     * @param DashboardFetcher $fetcher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function calendarAction(DashboardFetcher $fetcher)
    {
        $container = $fetcher->fetch(new \DateTime('now'));

        return $this->render('dashboard/index.html.twig', [
            'today' => $container->getExercisesForStartDate(),
            'weekAgo' => $container->getExercisesForWeekAgo(),
            'twoWeeksAgo' => $container->getExercisesForTwoWeeksAgo(),
        ]);
    }
}
