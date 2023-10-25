<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Form\OperationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED')]
#[Route('/operations')]
/**
 * Handle actions about operations (buy and sell).
 */
class OperationController extends AbstractController
{
    #[Route('/', name: 'app_operation')]
    public function index(Request $request): Response
    {
        $operation = new Operation();
        $form = $this->createForm(OperationType::class, $operation);

        $form->handleRequest($request);

        return $this->render('operation/index.html.twig', [
            'form' => $form,
        ]);
    }
}
