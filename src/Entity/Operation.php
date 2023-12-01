<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: OperationRepository::class)]
class Operation
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $typeOp;

    #[ORM\ManyToOne(inversedBy: 'operations')]
    #[ORM\JoinColumn(nullable: false)]
    private Team $operator;

    #[ORM\ManyToOne(inversedBy: 'operations')]
    #[ORM\JoinColumn(nullable: false)]
    private Player $player;

    #[ORM\Column]
    private float $amount;

    #[ORM\ManyToOne(inversedBy: 'operationConcerns')]
    private Team $Concern;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeOp(): ?string
    {
        return $this->typeOp;
    }

    public function setTypeOp(string $typeOp): static
    {
        $this->typeOp = $typeOp;

        return $this;
    }

    public function getOperator(): ?Team
    {
        return $this->operator;
    }

    public function setOperator(?Team $operator): static
    {
        $this->operator = $operator;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getConcern(): ?Team
    {
        return $this->Concern;
    }

    public function setConcern(?Team $Concern): static
    {
        $this->Concern = $Concern;

        return $this;
    }
}
