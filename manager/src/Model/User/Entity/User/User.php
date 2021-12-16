<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;

class User
{
    private const STATUS_NEW = 'new';
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';

    private Id $id;
    private ?Email $email;
    private ?string $passwordHash;
    private \DateTimeImmutable $created_at;
    private ?string $confirmToken;
    private string $status;

    /**
     * @var Network[]|ArrayCollection
     */
    private $network;

    public function __construct(Id $id, \DateTimeImmutable $created_at)
    {
        $this->id = $id;
        $this->created_at = $created_at;
        $this->status = self::STATUS_NEW;
        $this->network = new ArrayCollection();
    }

    public function signUpByEmail(Email $email, string $passwordHash, string $confirmToken): void
    {
        if (!$this->isNew()) {
            throw new \DomainException('User is already signed up.');
        }

        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->confirmToken = $confirmToken;
        $this->status = self::STATUS_WAIT;
    }

    public function confirmSignUp(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already confirmed.');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

    public function signUpByNetwork(string $network, string $identity): void
    {
        if (!$this->isNew()) {
            throw new \DomainException('User is already signed up.');
        }

        $this->attachNetwork($network, $identity);
        $this->status = self::STATUS_ACTIVE;
    }

    public function attachNetwork(string $network, string $identity): void
    {
        foreach ($this->network as $existing) {
            if ($existing->isForNetwork($network)) {
                throw new \DomainException('Network is already attached.');
            }
        }

        $this->network->add(new Network($this, $network, $identity));
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    public function getNetwork(): array
    {
        return $this->network->toArray();
    }
}
