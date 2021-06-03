<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * Undocumented function
     * 
     * @Route("/admin", name="admin_dashboard")
     *
     * @return Response
     */
    public function index(EntityManagerInterface $manager, StatsService $statsService): Response
    {
        $stats = $statsService->getStats();
        $bestAds = $statsService->getAdsStats("DESC");
        $worstAds = $statsService->getAdsStats("ASC");

        //compact() crée un tableau avec les clés données pour chaque variable du même nom (ex  : 'users' => $users)
        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'bestAds' => $bestAds,
            'worstAds' => $worstAds
        ]);
    }
}
