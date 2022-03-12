<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Request;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @var string
     */
    public string $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     * @var string
     */
    public string $password;
}
