<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFaceitChampionship extends Migration
{
    public function up(): void
    {
        Schema::create('championships', function (Blueprint $table) {
            $table->id();
            $table->uuid('faceit_id')->unique();
            $table->string('name', 64);
            $table->tinyInteger('region');
            $table->dateTime('started_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('championships');
    }
}
