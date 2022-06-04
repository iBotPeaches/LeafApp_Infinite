<?php
declare(strict_types = 1);

namespace App\Support\Analytics;

use App\Enums\AnalyticType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface AnalyticInterface
{
    public function type(): AnalyticType;
    public function title(): string;
    public function unit(): string;
    public function property(Model $model): string;
    public function builder(): Builder;
    public function result(): ?Model;
}
