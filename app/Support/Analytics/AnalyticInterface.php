<?php
declare(strict_types = 1);

namespace App\Support\Analytics;

use App\Enums\AnalyticType;
use App\Models\Analytic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface AnalyticInterface
{
    public function type(): AnalyticType;
    public function key(): string;
    public function title(): string;
    public function unit(): string;
    public function property(): string;
    public function displayProperty(Analytic $analytic): string;
    public function builder(): Builder;
    public function results(): ?Collection;
}
