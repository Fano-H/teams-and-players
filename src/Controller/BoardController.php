<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED')]
class BoardController extends AbstractController
{
    #[Route('/', name: 'app_board')]
    public function index(): Response
    {
        return $this->render('board/index.html.twig', [
            'controller_name' => 'BoardController',
        ]);
    }
}
