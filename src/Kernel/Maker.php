<?php

namespace Bloom\Kernel;

use Bloom\App;

class Maker
{
    public static function generate(string $path, string $template): void
    {
        $realPath = App::dir().$path;

        $templateFile = file_get_contents(__DIR__ . '/../Templates/' .$template.'.stub');

        if($templateFile) {
            file_put_contents($realPath, $templateFile);
        }
    }
}