<?php

namespace Gett\MyParcel\Database;

interface Migration
{
    public static function up(): bool;

    public static function down(): bool;
}
