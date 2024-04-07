<?php

namespace Bloom\Kernel;

use ReflectionAttribute;
use ReflectionClass;

class Provider
{
    protected static string $providerName;

    private function providerNameFromClassName(): string
    {
        $explodedName = explode('\\', static::class);
        return strtolower(substr(end($explodedName), 0, -8));
    }
    public function getProviderName(): string
    {
        return static::$providerName ?? $this->providerNameFromClassName();
    }
    public function getCommands(): array
    {
        $commands = [];
        $reflection = new ReflectionClass(static::class);

        foreach ($reflection->getMethods() as $method) {
            $attributes = array_filter(
                $method->getAttributes(Command::class),
                fn(ReflectionAttribute $attribute) => $attribute->getName() == Command::class
            );
            $commandAttribute = $attributes[0] ?? null;
            if(null != $commandAttribute)
            {
                $commandAttribute = $commandAttribute->newInstance();
                $commands[] = [
                    'name' =>
                        ($commandAttribute->name === '' ? static::getProviderName() : null) ??
                        ($method->getName() == '__init__'
                            ? static::getProviderName()
                            : static::getProviderName() . ':' . $method->getName()
                        ),
                    'description' => $commandAttribute->description,
                    'methodName' => $method->getName(),
                    'parameters' => array_map(fn($parameter) => $parameter->getName(), $method->getParameters()),
                ];
            }
        }
        return $commands;
    }
    public function getProviderHelp(): string
    {
        $commandsOfClass = $this->getCommands();

        return ConsoleStr::text($this->getProviderName() . ':')
                ->background(ConsoleStr::BG_GREEN)
                ->textColor(ConsoleStr::TEXT_BLACK) . PHP_EOL
            . join(
                PHP_EOL,
                array_map(fn($command) => ' ' .
                    $command['name']. ' - ' .
                    ConsoleStr::text($command['description'])
                        ->textColor(ConsoleStr::TEXT_YELLOW),
                    $commandsOfClass
                )
            )

            . PHP_EOL;
    }
    public function runCommand(string $commandName): bool
    {
        $methods = $this->getCommands();

        $filteredCommand = array_filter($methods, fn($command) => $command['name'] == $commandName);
        $command = array_shift($filteredCommand) ?? null;

        if(null != $command)
        {
            $arguments = [];
            foreach ($command['parameters'] as $key => $parameter) {
                $arguments[$parameter] = Console::getArg($key);
            }

            $this->{$command['methodName']}(...$arguments);
            echo PHP_EOL;
            return true;
        }
        return false;
    }
}