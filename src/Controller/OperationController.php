<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Form\OperationType;
use App\Repository\OperationRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function newOperation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $operation = new Operation();
        $form = $this->createForm(OperationType::class, $operation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();

                $initialOperatorBalance = (float) $data->getOperator()->getMoneyBalance();
                $initialConcernBalance = (float) $data->getConcern()->getMoneyBalance();

                $operationAmout = (float) $data->getAmount();

                $typeOfOperation = $data->getTypeOp();

                $newOperatorBalance = 0;
                $newConcernBalance = 0;

                $player = $data->getPlayer();

                if ('buy' === $typeOfOperation) {
                    if ($operationAmout > $initialOperatorBalance) {
                        return new Response('The operator team has lower balance than the amount to purchase !', 403);
                    }
                    $newOperatorBalance = $initialOperatorBalance - $operationAmout;
                    $newConcernBalance = $initialConcernBalance + $operationAmout;

                    $player->setTeam($data->getOperator());
                } elseif ('sell' == $typeOfOperation) {
                    if ($operationAmout > $initialConcernBalance) {
                        return new Response('The concern team has lower balance than the sold amount !', 403);
                    }
                    $newOperatorBalance = $initialOperatorBalance + $operationAmout;
                    $newConcernBalance = $initialConcernBalance - $operationAmout;

                    $player->setTeam($data->getConcern());
                } else {
                    $newOperatorBalance = $initialOperatorBalance;
                    $newConcernBalance = $initialConcernBalance;
                }

                $operation->getOperator()->setMoneyBalance($newOperatorBalance);
                $operation->getConcern()->setMoneyBalance($newConcernBalance);

                $entityManager->persist($operation);
                $entityManager->flush();

                return $this->render('operation/confirmation.html.twig', [
                    'operationType' => $operation->getTypeOp(),
                    'operationFrom' => $operation->getOperator()->getName(),
                    'operationTo' => $operation->getConcern()->getName(),
                    'operationPlayer' => $operation->getPlayer()->getName().' '.$operation->getPlayer()->getSurname(),
                    'operationAmount' => $operation->getAmount(),
                ]);
            } catch (\Exception $exc) {
            }
        }

        return $this->render('operation/index.html.twig', [
            'form' => $form,
        ]);
    }
}
