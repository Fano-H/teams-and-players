<?php

namespace App\Form;

use App\Entity\Operation;
use App\Entity\Player;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeOp', ChoiceType::class, [
                'choices' => [
                    'Sell' => 'sell',
                    'Buy' => 'buy',
                ],
                'placeholder' => 'Select operation',
                'label' => 'Type of operation',
                'attr' => [
                    'class' => 'custom-operation-type-select',
                ],
            ])
            ->add('amount', MoneyType::class, [
                'currency' => 'USD',
                'html5' => true,
                'attr' => [
                    'class' => 'custom-operation-amount',
                ],
            ])
            ->add('operator', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a team',
                'attr' => [
                    'class' => 'custom-operator',
                ],
            ])
            ->add('concern', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a team',
                'attr' => [
                    'class' => 'custom-concern',
                ],
            ])
            ->add('player', EntityType::class, [
                'class' => Player::class,
                'choice_label' => function (Player $player) {
                    return $player->getName().$player->getSurname();
                },
                'placeholder' => 'Select a player',
                'attr' => [
                    'class' => 'custom-concern-player',
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
    }
}
