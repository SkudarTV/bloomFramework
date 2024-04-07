<?php

namespace Bloom\Kernel;

abstract class Console
{
    public static function getCommand(): ?string
    {
        global $argv;
        return $argv[1] ?? null;
    }

    public static function getArg(int $argument): ?string
    {
        global $argv;
        return $argv[2 + $argument] ?? null;
    }

    public static function requestInput(string $textInput, ?Callable $condition = null): string
    {
        $input = null;
        $satisfied = false;
        while ($satisfied == false)
            {
                echo $textInput.':'.PHP_EOL.'> ';
                $fin = fopen ("php://stdin","r");
                $input = trim(fgets($fin));

                if(null != $condition && !$condition($input))
                {
                    echo ConsoleStr::text('This input isn\'t valid.')
                        ->background(ConsoleStr::BG_RED)
                        ->textColor(ConsoleStr::TEXT_BLACK).PHP_EOL;
                }
                else {
                    $satisfied = true;
                }
            }
        return $input;
    }
}