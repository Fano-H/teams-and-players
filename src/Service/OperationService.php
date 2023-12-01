<?php

namespace App\Service;

use App\Entity\Operation;
use Doctrine\ORM\EntityManagerInterface;

class OperationService
{
    public function __construct(private EntityManagerInterface $entityManagerInterface)
    {
    }

    /**
     * Process the creation of an operation.
     */
    public function processNewOperation(Operation &$operation): void
    {
        $initialOperatorBalance = (float) $operation->getOperator()->getMoneyBalance();
        $initialConcernBalance = (float) $operation->getConcern()->getMoneyBalance();

        $operationAmout = (float) $operation->getAmount();

        $typeOfOperation = $operation->getTypeOp();

        $player = $operation->getPlayer();

        if ('buy' === $typeOfOperation) {
            if ($operationAmout > $initialOperatorBalance) {
                throw new \Exception('The operator team has lower balance than the amount to purchase !', 1);
            }
            $newOperatorBalance = $initialOperatorBalance - $operationAmout;
            $newConcernBalance = $initialConcernBalance + $operationAmout;

            if ($operation->getOperator()) {
                $player->setTeam($operation->getOperator());
            }
        } elseif ('sell' === $typeOfOperation) {
            if ($operationAmout > $initialConcernBalance) {
                throw new \Exception('The concern team has lower balance than the sold amount !', 1);
            }
            $newOperatorBalance = $initialOperatorBalance + $operationAmout;
            $newConcernBalance = $initialConcernBalance - $operationAmout;

            if ($operation->getConcern()) {
                $player->setTeam($operation->getConcern());
            }
        } else {
            $newOperatorBalance = $initialOperatorBalance;
            $newConcernBalance = $initialConcernBalance;
        }

        $operation->getOperator()->setMoneyBalance($newOperatorBalance);
        $operation->getConcern()->setMoneyBalance($newConcernBalance);

        try {
            $this->entityManagerInterface->persist($operation);

            $this->entityManagerInterface->flush();
        } catch (\Exception $exc) {
            throw $exc;
        }
    }
}
