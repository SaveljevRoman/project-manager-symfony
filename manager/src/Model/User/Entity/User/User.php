<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

class User
{
    private Id $id;
    private Email $email;
    private string $passwordHash;
    private \DateTimeImmutable $created_at;

    public function __construct(Id $id, Email $email, string $passwordHash, \DateTimeImmutable $created_at)
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->created_at = $created_at;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->created_at;
    }

}
