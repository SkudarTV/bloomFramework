<?php

namespace Bloom\Providers;

use Bloom\App;
use Bloom\Kernel\Command;
use Bloom\Kernel\Console;
use Bloom\Kernel\ConsoleStr;
use Bloom\Kernel\Database\Migration;
use Bloom\Kernel\Maker;
use Bloom\Kernel\Provider;
use Throwable;

class MigrationProvider extends Provider
{
    /**
     * @throws Throwable
     */
    #[Command(description: 'Run all the migration file.')]
    public function run(): void
    {
        echo 'Running migration file...' . PHP_EOL;

        $dir = App::dir().'/database/migrations/*.php';

        foreach (glob($dir) as $item) {
            $class = require $item;
            if($class instanceof Migration)
            {
                try {
                    $class->up();
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

    /**
     * @throws Throwable
     */
    #[Command(description: 'Revert all the migration file.')]
    public function revert(): void
    {
        echo 'Running migration file...' . PHP_EOL;

        $dir = App::dir().'/database/migrations/*.php';

        foreach (array_reverse(glob($dir)) as $item) {
            $class = require $item;
            if($class instanceof Migration)
            {
                try {
                    $class->down();
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

    #[Command(description: 'Generate a migration file.')]
    public function make(?string $migrationName, ...$arg): void
    {
        if($migrationName == null)
        {
            $migrationName = Console::requestInput(
                'Migration name',
                fn($input) => $input != '' && !$this->existInMigration($input)
            );
        }
        elseif($this->existInMigration($migrationName)){
            echo ConsoleStr::text($migrationName.' migration file already exist.')->textColor(ConsoleStr::TEXT_RED);
            return;
        }

        if(!str_ends_with($migrationName, '.php')) $migrationName .= '.php';
        echo ConsoleStr::text('Generating '.$migrationName.' migration...')->textColor(ConsoleStr::TEXT_GREEN);
        Maker::generate('/database/migrations/'.$migrationName, 'migration');
    }

    private function existInMigration(string $name): bool
    {
        if(!str_ends_with($name, '.php')) $name .= '.php';
        return file_exists(App::dir().'/database/migrations/' . $name);
    }
}