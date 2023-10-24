<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED')]
#[Route('/teams')]
/**
 * Handle things relative to teams.
 */
class TeamController extends AbstractController
{
    #[Route('/', name: 'app_team_index')]
    /**
     * Index access teams listing.
     */
    public function index(): Response
    {
        return $this->render('team/index.html.twig', [
            'controller_name' => 'TeamController',
        ]);
    }

    #[Route('/new', name: 'app_team_new')]
    public function newTeam(): Response
    {
        return $this->render('team/new.html.twig', []);
    }
}
