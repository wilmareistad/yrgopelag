<?php

declare(strict_types=1);

namespace App;

use App\Config\Config;
use App\Database\Connection;
use Dotenv\Dotenv;
use PDO;

final class Bootstrap
{
    private static ?self $instance = null;

    private function __construct(
        private readonly Config $config,
        private readonly PDO $pdo,
    ) {
    }

    public static function init(string $baseDir): self
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once $baseDir . '/vendor/autoload.php';

        date_default_timezone_set('UTC');
        mb_internal_encoding('UTF-8');

        Dotenv::createImmutable($baseDir)->safeLoad();

        $config = Config::load($baseDir);
        $pdo = Connection::make($config);

        return self::$instance = new self($config, $pdo);
    }

    public function config(): Config
    {
        return $this->config;
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }
}
