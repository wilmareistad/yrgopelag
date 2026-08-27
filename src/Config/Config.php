<?php

declare(strict_types=1);

namespace App\Config;

final class Config
{
    private function __construct(
        private readonly string $databasePath,
        private readonly string $centralBankBaseUri,
        private readonly string $hotelUser,
        private readonly ?string $apiKey,
    ) {
    }

    public static function load(string $baseDir): self
    {
        $raw = require $baseDir . '/app/config.php';

        return new self(
            databasePath: $raw['database_path'],
            centralBankBaseUri: 'https://www.yrgopelag.se/centralbank/',
            hotelUser: 'Wilma',
            apiKey: $_ENV['API_KEY'] ?? null,
        );
    }

    public function databasePath(): string
    {
        return $this->databasePath;
    }

    public function centralBankBaseUri(): string
    {
        return $this->centralBankBaseUri;
    }

    public function hotelUser(): string
    {
        return $this->hotelUser;
    }

    public function apiKey(): ?string
    {
        return $this->apiKey;
    }
}
