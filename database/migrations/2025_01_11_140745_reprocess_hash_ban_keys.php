<?php

declare(strict_types=1);

use App\Models\PlayerBan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        PlayerBan::query()
            ->with('player')
            ->whereNotNull('key')
            ->get()
            ->each(function (PlayerBan $ban) {
                $ban->update([
                    'key' => md5(Str::slug($ban->message).$ban->player_id),
                ]);
            });
    }

    public function down(): void
    {
        //
    }
};
