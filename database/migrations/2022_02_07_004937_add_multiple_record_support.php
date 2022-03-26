<?php
declare(strict_types=1);

use App\Enums\Mode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleRecordSupport extends Migration
{
    public function up(): void
    {
        Schema::table('service_records', function (Blueprint $table) {
            $table->tinyInteger('mode')
                ->default(Mode::MATCHMADE_RANKED)
                ->after('player_id');

            $table->unique(['player_id', 'mode']);
        });
    }

    public function down(): void
    {
        Schema::table('service_records', function (Blueprint $table) {
            $table->dropUnique(['player_id', 'mode']);
            $table->dropColumn('mode');
        });
    }
}
