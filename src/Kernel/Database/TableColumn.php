<?php

namespace Bloom\Kernel\Database;

trait TableColumn
{
    private array $columns = [];
    public function id(): self
    {
        return $this->integer('id');
    }

    public function integer(string $columnName, int $size = 11, bool $nullable = false): self
    {
        $this->columns[] = ['name' => $columnName, 'type' => 'int', 'size' => $size, 'nullable' => $nullable];

        return $this;
    }

    public function string(string $columnName, int $size = 255, bool $nullable = false): self
    {
        $this->columns[] = ['name' => $columnName, 'type' => 'varchar', 'size' => $size, 'nullable' => $nullable];

        return $this;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }
}