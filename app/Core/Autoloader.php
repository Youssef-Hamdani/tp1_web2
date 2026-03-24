<?php

declare(strict_types=1);

namespace App\Core;

final class Autoloader
{
    private string $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, DIRECTORY_SEPARATOR);
    }

    public function register(): void
    {
        spl_autoload_register(array($this, 'autoload'));
    }

    private function autoload(string $className): void
    {
        $prefix = 'App\\';

        if (strpos($className, $prefix) !== 0) {
            return;
        }

        $relativeClass = substr($className, strlen($prefix));
        $filePath = $this->basePath . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

        if (is_file($filePath)) {
            require $filePath;
        }
    }
}

