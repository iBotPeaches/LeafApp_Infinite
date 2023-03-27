<?php

declare(strict_types=1);

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Category::query()->cursor()->each(function (Category $category) {
            if (is_numeric($category->uuid)) {
                /** @var Category|null $otherCategory */
                $otherCategory = Category::query()
                    ->whereNot('id', $category->id)
                    ->where('name', $category->name)
                    ->first();

                if ($otherCategory) {
                    DB::table('games')
                        ->where('category_id', $category->id)
                        ->update([
                            'category_id' => $otherCategory->id,
                        ]);

                    $category->deleteOrFail();
                }
            }
        });
    }

    public function down(): void
    {
        //
    }
};
