<?php

namespace Bloom\Kernel\Database;

trait ColumnSpecification
{
    private bool $isNullable = false;
    private bool $isPrimary = false;
    private string $type;
    private int $size;
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }


    public function nullable($nullable = true): self
    {
        $this->isNullable = $nullable;
        return $this;
    }

    public function primary($primary = true): self
    {

        $this->isPrimary = $primary;
        return $this;
    }

    public function type(string $type): self
    {

        $this->type = $type;
        return $this;
    }

    public function size(int $size): self
    {

        $this->size = $size;
        return $this;
    }
    public function generateSQLColumn(): string
    {
        return $this->name.' '.strtoupper($this->type).(isset($this->size) ? '('.$this->size.')' : '').
            ($this->isPrimary ? ' PRIMARY KEY': '').
            ($this->isNullable ? '' : ' NOT NULL')
            ;
    }

    public function __toString(): string
    {
        return $this->generateSQLColumn();
    }
}