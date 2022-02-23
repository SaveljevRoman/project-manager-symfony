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
    private ?ResetToken $resetToken;
    private string $status;
    private Role $role;

    /**
     * @var Network[]|ArrayCollection
     */
    private $network;

    private function __construct(Id $id, \DateTimeImmutable $created_at)
    {
        $this->id = $id;
        $this->created_at = $created_at;
        $this->role = Role::roleUser();
        $this->network = new ArrayCollection();
    }

    public static function signUpByEmail(
        Id                 $id,
        \DateTimeImmutable $created_at,
        Email              $email,
        string             $passwordHash,
        string             $confirmToken
    ): self
    {
        $user = new self($id, $created_at);
        $user->email = $email;
        $user->passwordHash = $passwordHash;
        $user->confirmToken = $confirmToken;
        $user->status = self::STATUS_WAIT;
        return $user;
    }

    public function confirmSignUp(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already confirmed.');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

    public static function signUpByNetwork(
        Id                 $id,
        \DateTimeImmutable $created_at,
        string             $network,
        string             $identity
    ): self
    {
        $user = new self($id, $created_at);
        $user->attachNetwork($network, $identity);
        $user->status = self::STATUS_ACTIVE;
        return $user;
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

    public function requestPasswordReset(ResetToken $resetToken, \DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('User is not active.');
        }

        if (empty($this->email)) {
            throw new \DomainException('Email is not specified.');
        }

        if (!empty($this->resetToken) && !$this->resetToken->isExpired($date)) {
            throw new \DomainException('Resetting is already requested.');
        }

        $this->resetToken = $resetToken;
    }

    public function passwordReset(\DateTimeImmutable $date, string $passwordHash): void
    {
        if (empty($this->resetToken)) {
            throw new \DomainException('Resetting is not requested.');
        }

        if ($this->resetToken->isExpired($date)) {
            throw new \DomainException('Reset token is expired.');
        }

        $this->passwordHash = $passwordHash;
        $this->resetToken = null;
    }

    public function changeRole(Role $role): void
    {
        if ($this->role->isEqual($role)) {
            throw new \DomainException('Role is already same.');
        }

        $this->role = $role;
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

    public function getResetToken(): ?ResetToken
    {
        return $this->resetToken;
    }

    public function getNetwork(): array
    {
        return $this->network->toArray();
    }

    public function getRole(): Role
    {
        return $this->role;
    }
}
