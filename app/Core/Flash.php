<?php

declare(strict_types=1);

namespace App\Core;

final class Flash
{
    private const SESSION_KEY = '_flash_messages';

    public function add(string $type, string $message): void
    {
        if (! isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = array();
        }

        $_SESSION[self::SESSION_KEY][] = array(
            'type' => $type,
            'message' => $message,
        );
    }

    public function pull(): array
    {
        $messages = $_SESSION[self::SESSION_KEY] ?? array();
        unset($_SESSION[self::SESSION_KEY]);

        return $messages;
    }
}

