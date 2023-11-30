<?php

namespace App\Service;

use App\Entity\Operation;
use Doctrine\ORM\EntityManagerInterface;

class OperationService
{
    public function __construct(private EntityManagerInterface $entityManagerInterface)
    {
    }

    public function processNewOperation(mixed $data, Operation $operation): mixed
    {
        $initialOperatorBalance = (float) $data->getOperator()->getMoneyBalance();
        $initialConcernBalance = (float) $data->getConcern()->getMoneyBalance();

        $operationAmout = (float) $data->getAmount();

        $typeOfOperation = $data->getTypeOp();

        $newOperatorBalance = 0;
        $newConcernBalance = 0;

        $player = $data->getPlayer();

        if ('buy' === $typeOfOperation) {
            if ($operationAmout > $initialOperatorBalance) {
                return 'operator-low-purchase';
            }
            $newOperatorBalance = $initialOperatorBalance - $operationAmout;
            $newConcernBalance = $initialConcernBalance + $operationAmout;

            if ($data->getOperator()) {
                $player->setTeam($data->getOperator());
            }
        } elseif ('sell' === $typeOfOperation) {
            if ($operationAmout > $initialConcernBalance) {
                return 'concern-low-sold-amount';
            }
            $newOperatorBalance = $initialOperatorBalance + $operationAmout;
            $newConcernBalance = $initialConcernBalance - $operationAmout;

            if ($data->getConcern()) {
                $player->setTeam($data->getConcern());
            }
        } else {
            $newOperatorBalance = $initialOperatorBalance;
            $newConcernBalance = $initialConcernBalance;
        }

        $operation->getOperator()->setMoneyBalance($newOperatorBalance);
        $operation->getConcern()->setMoneyBalance($newConcernBalance);

        $this->entityManagerInterface->persist($operation);
        $this->entityManagerInterface->flush();
    }
}
