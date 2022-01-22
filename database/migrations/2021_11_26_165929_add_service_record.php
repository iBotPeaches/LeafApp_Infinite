<?php

use App\Models\Player;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceRecord extends Migration
{
    public function up(): void
    {
        Schema::create('service_records', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Player::class)->constrained()->cascadeOnDelete();
            $table->double('kd', 8, 4);
            $table->double('kda', 8, 4);
            $table->unsignedInteger('total_score');
            $table->unsignedInteger('total_matches');
            $table->unsignedInteger('matches_won');
            $table->unsignedInteger('matches_lost');
            $table->unsignedInteger('matches_tied');
            $table->unsignedInteger('matches_left');

            $table->bigInteger('total_seconds_played');

            $table->unsignedInteger('kills');
            $table->unsignedInteger('deaths');
            $table->unsignedInteger('assists');
            $table->unsignedMediumInteger('betrayals');
            $table->unsignedMediumInteger('suicides');
            $table->unsignedInteger('vehicle_destroys');
            $table->unsignedInteger('vehicle_hijacks');
            $table->unsignedInteger('medal_count');
            $table->unsignedBigInteger('damage_taken');
            $table->unsignedBigInteger('damage_dealt');
            $table->unsignedBigInteger('shots_fired');
            $table->unsignedBigInteger('shots_landed');
            $table->unsignedBigInteger('shots_missed');

            $table->double('accuracy', 5, 2);
            $table->unsignedSmallInteger('kills_melee');
            $table->unsignedSmallInteger('kills_grenade');
            $table->unsignedSmallInteger('kills_headshot');
            $table->unsignedSmallInteger('kills_power');

            $table->unsignedSmallInteger('assists_emp');
            $table->unsignedSmallInteger('assists_driver');
            $table->unsignedSmallInteger('assists_callout');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_records');
    }
}
