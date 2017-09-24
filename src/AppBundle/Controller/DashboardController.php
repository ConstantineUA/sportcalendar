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
     * @Route("/", name="homepage")
     *
     * @param DashboardFetcher $fetcher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(DashboardFetcher $fetcher)
    {
        $data = $fetcher->fetch(new \DateTime('now'));

        return $this->render('dashboard/index.html.twig', [
            'data' => array_values($data),
        ]);
    }
}
