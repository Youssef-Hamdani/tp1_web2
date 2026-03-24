<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class View
{
    private string $viewsPath;
    private string $appName;
    private array $imageCredits;

    public function __construct(string $viewsPath, string $appName, array $imageCredits = array())
    {
        $this->viewsPath = rtrim($viewsPath, DIRECTORY_SEPARATOR);
        $this->appName = $appName;
        $this->imageCredits = $imageCredits;
    }

    public function render(string $template, array $data = array()): void
    {
        $contentTemplate = $this->resolvePath($template);
        $title = $data['title'] ?? $this->appName;
        $appName = $this->appName;
        $imageCredits = $this->imageCredits;
        $ui = new Ui();

        extract($data, EXTR_SKIP);

        require $this->viewsPath . DIRECTORY_SEPARATOR . 'layout.php';
    }

    private function resolvePath(string $template): string
    {
        $path = $this->viewsPath . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $template) . '.php';

        if (! is_file($path)) {
            throw new RuntimeException(sprintf('Vue introuvable: %s', $template));
        }

        return $path;
    }
}
