<?php

use Bloom\App;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once __DIR__.'/../../vendor/autoload.php';

/**
 * @throws \Twig\Error\SyntaxError
 * @throws \Twig\Error\RuntimeError
 * @throws \Twig\Error\LoaderError
 */
function view(string $viewName, array $parameters = []): void
{
    $loader = new FilesystemLoader(App::dir().'/resources/views');
    $twig = new Environment($loader);

    echo $twig->render($viewName, $parameters);
}