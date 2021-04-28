<?php

namespace App\Framework;

class View
{
    private string $templatePath;

    public function __construct(string $templatePath)
    {
        $this->templatePath = $templatePath;
    }

    public function render(string $path, array $params = [], int $statusCode = 200)
    {
        http_response_code($statusCode);

        foreach ( $params as $name => $value ) {
            $$name = $value;
        }

        ob_start();
        /** @noinspection PhpIncludeInspection */
        include  $this->getViewPath($path);
        $viewContent = ob_get_clean();

        return $viewContent;
    }

    public function jsonRender($data, int $statusCode = 200): string
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        return json_encode($data, 0);
    }

    private function getViewPath($path): string
    {
        $path = trim($path, '/');
        return $this->templatePath . "/{$path}";
    }
}