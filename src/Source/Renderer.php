<?php

namespace Source;

use Twig\Environment;

class Renderer
{
    public static function view(string $viewPath, array $params = [])
    {
        $loader = new \Twig\Loader\FilesystemLoader(BASE_VIEW_PATH);
        $twig = new \Twig\Environment($loader);
        echo $twig->render($viewPath . ".twig", $params);
    }
}
