<?php

namespace Bloom\Providers;

use Bloom\App;
use Bloom\Kernel\Command;
use Bloom\Kernel\Console;
use Bloom\Kernel\ConsoleStr;
use Bloom\Kernel\Database\Seeder;
use Bloom\Kernel\Maker;
use Bloom\Kernel\Provider;
use Throwable;

class SeederProvider extends Provider
{
    /**
     * @throws Throwable
     */
    #[Command(description: 'Run all the migration file.')]
    public function run(): void
    {
        echo 'Running seeder file...' . PHP_EOL;

        $dir = App::dir().'/database/seeders/*.php';

        foreach (glob($dir) as $item) {
            $class = require $item;
            if($class instanceof Seeder)
            {
                try {
                    $class->run();
                    echo ConsoleStr::text($item.' - '. 'SUCCESS')
                            ->textColor(ConsoleStr::TEXT_GREEN)
                        .PHP_EOL;
                }
                catch (Throwable $exception)
                {
                    echo ConsoleStr::text($item.' - '.'FAILED')
                            ->textColor(ConsoleStr::TEXT_RED)
                        .PHP_EOL;
                    throw $exception;
                }

            }
        }

        sleep(1);
    }

    #[Command(description: 'Generate a seeder file.')]
    public function make(?string $seederName): void
    {
        if($seederName == null)
        {
            $seederName = Console::requestInput(
                'Seeder name',
                fn($input) => $input != '' && !$this->existInSeeder($input)
            );
        }
        elseif($this->existInSeeder($seederName)){
            echo ConsoleStr::text($seederName.' seeder file already exist.')->textColor(ConsoleStr::TEXT_RED);
            return;
        }

        if(!str_ends_with($seederName, '.php')) $seederName .= '.php';
        echo ConsoleStr::text('Generating '.$seederName.' seeder...')->textColor(ConsoleStr::TEXT_GREEN);
        Maker::generate('/database/seeders/'.$seederName, 'seeder');
    }

    private function existInSeeder(string $name): bool
    {
        if(!str_ends_with($name, '.php')) $name .= '.php';
        return file_exists(App::dir().'/database/seeders/' . $name);
    }
}