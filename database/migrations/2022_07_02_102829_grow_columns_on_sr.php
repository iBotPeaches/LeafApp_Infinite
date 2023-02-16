<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_records', function (Blueprint $table) {
            $table->unsignedInteger('kills_melee')->change();
            $table->unsignedInteger('kills_grenade')->change();
            $table->unsignedInteger('kills_headshot')->change();
            $table->unsignedInteger('kills_power')->change();

            $table->unsignedInteger('assists_emp')->change();
            $table->unsignedInteger('assists_driver')->change();
            $table->unsignedInteger('assists_callout')->change();
        });
    }

    public function down(): void
    {
        Schema::table('service_records', function (Blueprint $table) {
            $table->unsignedSmallInteger('kills_melee')->change();
            $table->unsignedSmallInteger('kills_grenade')->change();
            $table->unsignedSmallInteger('kills_headshot')->change();
            $table->unsignedSmallInteger('kills_power')->change();

            $table->unsignedSmallInteger('assists_emp')->change();
            $table->unsignedSmallInteger('assists_driver')->change();
            $table->unsignedSmallInteger('assists_callout')->change();
        });
    }
};
