<?php

declare(strict_types=1);

use App\Enums\ChampionshipType;
use App\Models\Championship;
use App\Services\FaceIt\TournamentInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('championships', function (Blueprint $table) {
            $table->tinyInteger('type')->after('region')->default(ChampionshipType::DOUBLE_ELIM);
        });

        /** @var TournamentInterface $client */
        $client = resolve(TournamentInterface::class);

        Championship::query()->each(function (Championship $championship) use ($client) {
            $client->championship($championship->faceit_id);
        });
    }

    public function down(): void
    {
        Schema::table('championships', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
