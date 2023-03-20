<?php

declare(strict_types=1);

namespace App\Support\Modes;

use App\Enums\Outcome;
use App\Models\Category;
use App\Models\Map;
use stdClass;

class ModeResult
{
    public Outcome $outcome;

    public int $mapId;

    public ?Map $map = null;

    public int $categoryId;

    public ?Category $category = null;

    public int $total;

    public function __construct(stdClass $data)
    {
        $this->outcome = Outcome::coerce($data->outcome);
        $this->mapId = $data->map_id;
        $this->categoryId = $data->category_id;
        $this->total = $data->total;
    }
}
