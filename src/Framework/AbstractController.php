<?php


namespace App\Framework;


class AbstractController
{
    private ?View $view = null;

    public function render(string $path, array $params = [], int $statusCode = 200)
    {
        return $this->getView()->render($path, $params, $statusCode);
    }

    public function jsonRender($data, int $statusCode = 200): string
    {
        return $this->getView()->jsonRender($data, $statusCode);
    }

    private function getView():View
    {
        if(null === $this->view) {
            $this->view = Container::getInstance()->get(View::class);
        }
        return $this->view;
    }
}