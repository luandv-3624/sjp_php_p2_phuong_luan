<?php

namespace App\Repositories\Auth;

interface PasswordResetRepositoryInterface
{
    public function createToken(string $email, string $token): void;
    public function findByToken(string $email, string $plainToken);
    public function deleteByEmail(string $email): void;
}
