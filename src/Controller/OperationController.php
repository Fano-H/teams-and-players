<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Form\OperationType;
use App\Repository\OperationRepository;
use App\Service\OperationService;
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
    public function operationList(Request $request, OperationRepository $operationRepository): Response
    {
        $operations = $operationRepository->findAll();

        return $this->render('operation/list.html.twig', [
            'operations' => $operations,
        ]);
    }

    #[Route('/new', name: 'app_operation_new')]
    public function newOperation(Request $request, OperationService $operationService): Response
    {
        $operation = new Operation();
        $form = $this->createForm(OperationType::class, $operation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();

                if ('operator-low-purchase' === $operationService->processNewOperation($data, $operation)) {
                    $this->addFlash(
                        'danger',
                        'The operator team has lower balance than the amount to purchase !'
                    );

                    return $this->render('operation/index.html.twig', [
                        'form' => $form,
                    ]);
                }

                if ('concern-low-sold-amount' === $operationService->processNewOperation($data, $operation)) {
                    $this->addFlash(
                        'danger',
                        'The concern team has lower balance than the sold amount !'
                    );

                    return $this->render('operation/index.html.twig', [
                        'form' => $form,
                    ]);
                }

                return $this->render('operation/confirmation.html.twig', [
                    'operationType' => $operation->getTypeOp(),
                    'operationFrom' => $operation->getOperator()->getName(),
                    'operationTo' => $operation->getConcern()->getName(),
                    'operationPlayer' => $operation->getPlayer()->getName().' '.$operation->getPlayer()->getSurname(),
                    'operationAmount' => $operation->getAmount(),
                ]);
            } catch (\Exception $exc) {
                return new Response($exc->getMessage());
            }
        }

        return $this->render('operation/index.html.twig', [
            'form' => $form,
        ]);
    }
}
