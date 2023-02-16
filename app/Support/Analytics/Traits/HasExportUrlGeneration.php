<?php

declare(strict_types=1);

namespace App\Support\Analytics\Traits;

use Illuminate\Support\Str;

trait HasExportUrlGeneration
{
    public function displayExportUrl(int $count): string
    {
        return url('/storage/top-ten/'.$this->slug($count).'.csv');
    }

    public function slug(int $count): string
    {
        return Str::slug($this->title().' -top-'.$count);
    }
}
