<?php

namespace Bloom\Kernel\Database;

final class Column
{
    use ColumnSpecification;

    public static function integer($columnName, $size = null): Column
    {
        $instance = (new self($columnName))
            ->type('INT');
        if($size !== null) $instance->size($size);

        return $instance;
    }

    public static function id($columnName = 'id'): Column
    {
        return self::integer($columnName)
            ->primary()
            ->nullable(false);

    }
}