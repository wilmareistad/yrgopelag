<?php

declare(strict_types=1);

namespace App\Services;

use App\Entities\User;
use App\Repositories\UserRepository;
use App\Support\Redirect;
use App\Support\Session;

final class AuthService
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly Session $session,
    ) {
    }

    public function attempt(string $email, string $password): bool
    {
        $user = $this->users->findByEmail($email);

        if ($user === null || !$user->verifyPassword($password)) {
            return false;
        }

        session_regenerate_id(true);
        $this->session->setUserEmail($user->email);

        return true;
    }

    public function logout(): void
    {
        $this->session->destroy();
    }

    public function currentUser(): ?User
    {
        $email = $this->session->userEmail();

        return $email === null ? null : $this->users->findByEmail($email);
    }

    public function requireLogin(): User
    {
        $user = $this->currentUser();

        if ($user === null) {
            Redirect::to('/app/login.php');
        }

        return $user;
    }
}
