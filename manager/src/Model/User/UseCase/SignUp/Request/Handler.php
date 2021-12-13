<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Request;

use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
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
        $email = new Email($command->email);

        if ($this->userRepository->hasByEmail($email)) {
            throw new \DomainException('User already exists.');
        }

        $user = new User(
            Id::next(),
            $email,
            $this->passwordHasher->hash($command->password),
            new \DateTimeImmutable()
        );

        $this->userRepository->add($user);
        $this->flusher->flush();
    }
}
