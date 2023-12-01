<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
#[UniqueEntity('name')]
class Team
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255, unique: true)]
    private string $name;

    #[ORM\Column(nullable: true)]
    private float $moneyBalance;

    #[ORM\Column(length: 255, nullable: true)]
    private string $country;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: Player::class, cascade: ['persist'])]
    private Collection $players;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: Operation::class)]
    private Collection $operations;

    #[ORM\OneToMany(mappedBy: 'Concern', targetEntity: Operation::class)]
    private Collection $operationConcerns;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->operations = new ArrayCollection();
        $this->operationConcerns = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getMoneyBalance(): ?float
    {
        return $this->moneyBalance;
    }

    public function setMoneyBalance(?float $moneyBalance): static
    {
        $this->moneyBalance = $moneyBalance;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): static
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): static
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Operation>
     */
    public function getOperations(): Collection
    {
        return $this->operations;
    }

    public function addOperation(Operation $operation): static
    {
        if (!$this->operations->contains($operation)) {
            $this->operations->add($operation);
            $operation->setoperator($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): static
    {
        if ($this->operations->removeElement($operation)) {
            // set the owning side to null (unless already changed)
            if ($operation->getOperator() === $this) {
                $operation->setOperator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Operation>
     */
    public function getOperationConcerns(): Collection
    {
        return $this->operationConcerns;
    }

    public function addOperationConcern(Operation $operationConcern): static
    {
        if (!$this->operationConcerns->contains($operationConcern)) {
            $this->operationConcerns->add($operationConcern);
            $operationConcern->setConcern($this);
        }

        return $this;
    }

    public function removeOperationConcern(Operation $operationConcern): static
    {
        if ($this->operationConcerns->removeElement($operationConcern)) {
            // set the owning side to null (unless already changed)
            if ($operationConcern->getConcern() === $this) {
                $operationConcern->setConcern(null);
            }
        }

        return $this;
    }
}
