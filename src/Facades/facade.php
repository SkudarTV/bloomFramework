<?php

use Bloom\App;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

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