<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Reset\Reset;

use App\Model\Flusher;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\PasswordHasher;

class Handler
{
    private UserRepository $userRepository;
    private PasswordHasher $passwordHasher;
    private Flusher $flusher;

    public function __construct(UserRepository $userRepository, PasswordHasher $passwordHasher, Flusher $flusher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        if (!$user = $this->userRepository->findByConfirmToken($command->token)) {
            throw new \DomainException('Incorrect or confirmed token.');
        }

        $user->passwordReset(
            new \DateTimeImmutable(),
            $this->passwordHasher->hash($command->password)
        );

        $this->flusher->flush();
    }
}
