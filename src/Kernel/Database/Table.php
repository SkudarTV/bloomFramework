<?php

namespace Bloom\Kernel\Database;

use Closure;

class Table
{
    use Column;
    private string $name;
    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function create(string $name, Closure $callback): self
    {
        $instance = new self($name);

        $callback->call($instance, table: $instance);

        return $instance;
    }

    public function save(): bool
    {
        return true;
    }

    public function getCreateQuery(): string
    {
        $query = 'CREATE TABLE ' . $this->name . PHP_EOL .'('. PHP_EOL;
        foreach ($this->columns as $column) {
            $query .= '    '.$column['name'].' '.strtoupper($column['type']).'('.$column['size'].'),' .PHP_EOL;
        }
        $query .= ')';
        return $query;
    }
}