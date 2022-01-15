<?php
declare(strict_types=1);

namespace App\Models\Traits;

use App\Support\Csr\CsrHelper;

/**
 * @property-read string $level
 * @property-read string $level_image
 * @property-read string $csr_change
 * @property-read int $csr_change_raw
 * @property-read string $csr_rank_change_message
 */
trait HasCsr
{
    public function setPreCsrAttribute(?int $value): void
    {
        $this->attributes['pre_csr'] = $value === -1 ? null : $value;
    }

    public function setPostCsrAttribute(?int $value): void
    {
        $this->attributes['post_csr'] = $value === -1 ? null : $value;
    }

    public function getLevelAttribute(): string
    {
        return CsrHelper::getCsrFromValue($this->pre_csr)->title;
    }

    public function getLevelImageAttribute(): string
    {
        return CsrHelper::getCsrFromValue($this->pre_csr)->url();
    }

    public function getCsrChangeRawAttribute(): int
    {
        return $this->post_csr - $this->pre_csr;
    }

    public function getCsrChangeAttribute(): string
    {
        $difference = $this->csr_change_raw;

        return $difference > 0 ? '+' . $difference : (string) $difference;
    }

    public function getCsrRankChangeMessageAttribute(): ?string
    {
        $preCsr = CsrHelper::getCsrFromValue($this->pre_csr);
        $postCsr = CsrHelper::getCsrFromValue($this->post_csr);

        if ($preCsr->isDifferent($postCsr)) {
            $message = $postCsr > $preCsr ? 'moved to ' : 'fell to ';
            return $message . $postCsr->title;
        }

        return null;
    }
}
