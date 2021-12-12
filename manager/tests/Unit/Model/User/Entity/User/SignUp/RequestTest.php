<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\User\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = new User(
            $id = Uuid::uuid4()->toString(),
            $email = 'test@app.test',
            $passwordHash = 'passwordHash',
            $created_at = new \DateTimeImmutable()
        );

        self::assertEquals($id, $user->getId());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($passwordHash, $user->getPasswordHash());
        self::assertEquals($created_at, $user->getCreatedAt());
    }
}