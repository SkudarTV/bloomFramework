<?php

namespace Bloom\Kernel;

use Attribute;

#[Attribute]
class Command{
    public function __construct(
        public ?string $name = null,
        public string $description = "Default description.",
        public array $parameters = [],
    ) {}
}