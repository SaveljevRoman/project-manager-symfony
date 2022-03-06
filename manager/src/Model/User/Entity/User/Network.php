<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_user_networks", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"network", "identity"})
 * })
 */
class Network
{
    /**
     * @var string
     * @ORM\Column(type="guid")
     * @ORM\Id
     */
    private string $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="network")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private User $user;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private string $network;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private string $identity;

    public function __construct(User $user, string $network, string $identity)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->user = $user;
        $this->network = $network;
        $this->identity = $identity;
    }

    public function isForNetwork(string $network): bool
    {
        return $this->network === $network;
    }

    public function getNetwork(): string
    {
        return $this->network;
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }
}
