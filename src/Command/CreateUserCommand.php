<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Add an admin user to log in into the app',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private ValidatorInterface $validator,
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManagerInterface,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email of the user')
            ->addArgument('password', InputArgument::REQUIRED, 'Plain password of the user')
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $email = $io->ask('Enter the addresse email of the user admin', null, function ($eml) {
            if (empty(trim($eml))) {
                throw new \RuntimeException('Email cannot be empty.');
            }
            $emailConstraint = new Assert\Email();

            $errEmail = $this->validator->validate($eml, $emailConstraint);

            if ($errEmail->count()) {
                throw new \RuntimeException('Invalid email');
            }

            return $eml;
        });

        $input->setArgument('email', $email);

        $pass = $io->ask('Enter plain password for the user admin', null, function ($pwd) {
            if (empty(trim($pwd))) {
                throw new \RuntimeException('Password cannot be empty.');
            }

            $passLength = new Assert\Length(min: 5);

            $errPass = $this->validator->validate($pwd, $passLength);

            if ($errPass->count()) {
                throw new \RuntimeException($errPass[0]->getMessage());
            }

            return $pwd;
        });

        $input->setArgument('password', $pass);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $user = new User();
        $user->setEmail($email);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);

        $user->setPassword($hashedPassword);

        $this->entityManagerInterface->persist($user);
        $this->entityManagerInterface->flush();

        $io->success('User created. You can log in using this user.');

        return Command::SUCCESS;
    }
}
