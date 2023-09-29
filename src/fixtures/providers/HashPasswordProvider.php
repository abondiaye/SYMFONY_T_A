<?php

namespace App\Fixtures\Providers;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class HashPasswordProvider
{
    public function __construct(
        private UserPasswordHasherInterface $encoder
    ) {
    }

    public function hashPassword(string $plainPassword): string
    {
        $user = new User();
        return $this->encoder->hashPassword(new User(), $plainPassword);
    }
}
