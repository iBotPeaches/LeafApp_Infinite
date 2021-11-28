<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropUselessTables extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('personal_access_tokens');
    }

    public function down(): void
    {
        throw new InvalidArgumentException('Reversing this migration is not supported.');
    }
}
