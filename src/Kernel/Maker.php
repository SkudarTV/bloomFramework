<?php

namespace Bloom\Kernel;

use Bloom\App;

class Maker
{
    public static function generate(string $path, string $template): void
    {
        $pathParts = explode('/', $path);

        $path = App::dir();
        for ($i = 1; $i < count($pathParts)-1; $i++) {
            $pathToDirectory = $path.'/'.$pathParts[$i];
            if(!file_exists($pathToDirectory)) mkdir($pathToDirectory);
            $path .= '/'.$pathParts[$i];
        }
        $path .= '/'. end($pathParts);

        $templateFile = file_get_contents(__DIR__ . '/../Templates/' .$template.'.stub');

        if($templateFile) {
            file_put_contents($path, $templateFile);
        }
    }
}