<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Request;

use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\SignUpConfirmTokenizer;
use App\Model\User\Service\ConfirmTokenSender;
use App\Model\User\Service\PasswordHasher;

class Handler
{
    private UserRepository $userRepository;
    private PasswordHasher $passwordHasher;
    private Flusher $flusher;
    private SignUpConfirmTokenizer $tokenizer;
    private ConfirmTokenSender $sender;

    public function __construct(
        UserRepository         $userRepository,
        PasswordHasher         $passwordHasher,
        SignUpConfirmTokenizer $tokenizer,
        ConfirmTokenSender     $sender,
        Flusher                $flusher
    )
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->tokenizer = $tokenizer;
        $this->sender = $sender;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->userRepository->hasByEmail($email)) {
            throw new \DomainException('User already exists.');
        }

        $user = User::signUpByEmail(
            Id::next(),
            new \DateTimeImmutable(),
            $email,
            $passwordHash = $this->passwordHasher->hash($command->password),
            $token = $this->tokenizer->generate(),
        );

        $this->userRepository->add($user);

        $this->sender->send($email, $token);

        $this->flusher->flush();
    }
}
