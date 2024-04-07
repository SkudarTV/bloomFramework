<?php

namespace Bloom\Kernel\Database;

interface Migration
{
    public static function up(): void;
    public static function down(): void;
}