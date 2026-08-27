<?php

declare(strict_types=1);

namespace App\Support;

final class Session
{
    public function setUserEmail(string $email): void
    {
        $_SESSION['user_email'] = $email;
    }

    public function userEmail(): ?string
    {
        return $_SESSION['user_email'] ?? null;
    }

    public function isLoggedIn(): bool
    {
        return $this->userEmail() !== null;
    }

    public function flashError(string $message): void
    {
        $_SESSION['errors'][] = $message;
    }

    /** @return string[] */
    public function pullErrors(): array
    {
        $errors = $_SESSION['errors'] ?? [];
        $_SESSION['errors'] = [];

        return $errors;
    }

    public function destroy(): void
    {
        $_SESSION = [];
        session_unset();
        session_destroy();
    }
}
