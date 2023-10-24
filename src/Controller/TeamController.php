<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED')]
#[Route('/teams')]
class TeamController extends AbstractController
{
    #[Route('/', name: 'app_team_index')]
    public function index(): Response
    {
        return $this->render('team/index.html.twig', [
            'controller_name' => 'TeamController',
        ]);
    }
}
