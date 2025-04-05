<?php

declare(strict_types=1);

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

    public function slug(int $count): string;

    public function displayProperty(Analytic $analytic): string;

    public function displayExportUrl(int $count): string;

    public function baseBuilder(): Builder;

    public function resultBuilder(): Builder;

    public function results(int $limit = 10): ?Collection;

    public function csvHeader(): array;

    public function csvData(?Collection $collection): array;
}
