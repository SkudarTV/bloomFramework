<?php

namespace Bloom;

use Bloom\Kernel\Console;
use Bloom\Kernel\Provider;

class App
{
    private array $providers = [];
    private static self $instance;
    private static string|false $dir;

    private function __construct(){}
    public static function init(): self
    {
        self::$instance = new self();
        return self::$instance;
    }
    public static function getInstance(): self
    {
        return self::$instance ?? self::init();
    }
    public function discoverProviders(): self
    {
        foreach (glob(__DIR__ . '/Providers/*Provider.php') as $provider) {
            $filePath = explode('/', $provider);
            $className = 'Bloom\\Providers\\' . substr(end($filePath), 0, -4);
            $providerInstance = new $className;

            if(is_subclass_of($providerInstance, Provider::class)) {
                $this->providers[] = $providerInstance;
            }
        }
        return $this;
    }
    public function addProvider(string $provider): self
    {
        $this->providers[] = new $provider;
        return $this;
    }
    public function run(): void
    {
        self::setDirectory(getcwd());
        $command = Console::getCommand();

        if(!$command)
        {
            echo join(
                PHP_EOL,
                array_map(
                    fn(Provider $provider) => $provider->getProviderHelp(),
                    $this->providers
                )
            ). PHP_EOL;
            return;
        }

        $splitCommand = explode(':', $command);

        $providers = array_filter($this->providers, function (Provider $provider) use ($splitCommand) {
            return $provider->getProviderName() == $splitCommand[0];
        });
        $provider = array_shift($providers) ?? null;

        $provider?->runCommand($command);
    }
    public static function setDirectory(false|string $getcwd)
    {
        self::$dir = $getcwd;
    }
    public static function dir(): false|string
    {
        return self::$dir;
    }
    public function setRouting(...$routes): self
    {
        foreach ($routes as $route)
        {
            if(file_exists(self::dir() . $route))
            {
                require self::dir() . $route;
            }
        }
        return $this;
    }
    public function handleRequest(): void
    {
        global $argv;
        print_r($argv);

        echo $_SERVER['REQUEST_METHOD'];
    }
}