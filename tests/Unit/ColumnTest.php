<?php

use Bloom\Kernel\Database\Column;

it('crash without type', function () {
    $column = (string) (new Column('name'));
})->throws(Error::class)->group('Column definition');

test('column with type and nullable', function () {
    expect(
        (string) (new Column('name'))
            ->type('INT')
            ->nullable(false)
    )->toBe('name INT NOT NULL');
})->group('Column definition');

test('column with type, size and nullable', function () {
    expect(
        (string) (new Column('name'))
            ->type('INT')
            ->size(11)
            ->nullable(false)
    )
        ->toBe('name INT(11) NOT NULL');
})->group('Column definition');

test('column with type, size, primary and nullable', function () {
    expect(
        (string) (new Column('name'))
            ->type('INT')
            ->size(11)
            ->primary()
            ->nullable(false)
    )
        ->toBe('name INT(11) PRIMARY KEY NOT NULL');
})->group('Column definition');

test('column with type, size, primary and not nullable', function () {
    expect(
        (string) (new Column('name'))
            ->type('VARCHAR')
            ->size(5)
            ->primary()
            ->nullable()
    )
        ->toBe('name VARCHAR(5) PRIMARY KEY');
})->group('Column definition');

test('id with no specific name', function () {
    expect(
        (string) Column::id()
    )
        ->toBe('id INT PRIMARY KEY NOT NULL');
})->group('Column prefab');

test('id with a specific name', function () {
    expect(
        (string) Column::id('identifier')
    )
        ->toBe('identifier INT PRIMARY KEY NOT NULL');
})->group('Column prefab');
