<?php

declare(strict_types=1);

use App\Enums\Input;
use App\Enums\Queue;
use App\Models\Playlist;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const SOLO_DUO_MNK = 'f7eb8c71-fedb-4696-8c0f-96025e285ffd';

    private const SOLO_DUO_CONTROLLER = 'f7f30787-f607-436b-bdec-44c65bc2ecef';

    private const OPEN_CROSSPLAY_ARENA = 'edfef3ac-9cbe-4fa2-b949-8f29deafd483';

    public function up(): void
    {
        Schema::table('csrs', function (Blueprint $table) {
            $table
                ->foreignIdFor(Playlist::class)
                ->nullable()
                ->after('player_id')
                ->constrained()
                ->restrictOnDelete();
        });

        $soloDuoMnk = Playlist::query()
            ->where('uuid', self::SOLO_DUO_MNK)
            ->first();

        $soloDuoController = Playlist::query()
            ->where('uuid', self::SOLO_DUO_CONTROLLER)
            ->first();

        $openCrossplay = Playlist::query()
            ->where('uuid', self::OPEN_CROSSPLAY_ARENA)
            ->first();

        if ($soloDuoMnk instanceof Playlist) {
            DB::table('csrs')
                ->whereNull('playlist_id')
                ->where('queue', Queue::SOLO_DUO)
                ->where('input', Input::KBM)
                ->update([
                    'playlist_id' => $soloDuoMnk->id,
                ]);
        }

        if ($soloDuoController instanceof Playlist) {
            DB::table('csrs')
                ->whereNull('playlist_id')
                ->where('queue', Queue::SOLO_DUO)
                ->where('input', Input::CONTROLLER)
                ->update([
                    'playlist_id' => $soloDuoController->id,
                ]);
        }

        if ($openCrossplay instanceof Playlist) {
            DB::table('csrs')
                ->whereNull('playlist_id')
                ->where('queue', Queue::OPEN)
                ->where('input', Input::CROSSPLAY)
                ->update([
                    'playlist_id' => $soloDuoController->id,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('csrs', function (Blueprint $table) {
            $table->dropForeignIdFor(Playlist::class);
        });
    }
};
