<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

class User
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $passwordHash;

    /**
     * @var \DateTimeImmutable
     */
    private \DateTimeImmutable $created_at;

    public function __construct(string $id, string $email, string $passwordHash, \DateTimeImmutable $created_at)
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->created_at = $created_at;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
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
