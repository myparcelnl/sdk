<?php

namespace Gett\MyparcelBE\Database;

interface Migration
{
    public static function up(): bool;

    public static function down(): bool;
}
