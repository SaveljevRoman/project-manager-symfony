<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Reset\Request;

use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\ResetTokenizer;
use App\Model\User\Service\ResetTokenSender;

class Handler
{
    private UserRepository $userRepository;
    private ResetTokenizer $resetTokenizer;
    private Flusher $flusher;
    private ResetTokenSender $resetTokenSender;

    public function __construct(
        UserRepository   $userRepository,
        ResetTokenizer   $resetTokenizer,
        Flusher          $flusher,
        ResetTokenSender $resetTokenSender
    )
    {
        $this->userRepository = $userRepository;
        $this->resetTokenizer = $resetTokenizer;
        $this->flusher = $flusher;
        $this->resetTokenSender = $resetTokenSender;
    }

    public function handle(Command $command): void
    {
        $user = $this->userRepository->getByEmail(new Email($command->email));

        $user->requestPasswordReset(
            $this->resetTokenizer->generate(),
            new \DateTimeImmutable()
        );

        $this->flusher->flush();

        $this->resetTokenSender->send($user->getEmail(), $user->getResetToken());
    }
}
