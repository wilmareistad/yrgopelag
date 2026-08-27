<?php

declare(strict_types=1);

namespace App\Support;

final class View
{
    public static function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    public static function render(string $templatePath, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require $templatePath;
    }
}
