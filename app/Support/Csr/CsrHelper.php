<?php
declare(strict_types=1);

namespace App\Support\Csr;

class CsrHelper
{
    public static function getCsrFromValue(?int $value, ?int $matchesRemaining): Csr
    {
        // Since there is 50 CSR per level and 6 levels per class
        $rankClass = $value / 50;
        $subTier = (int) ceil($rankClass + 0.001);
        $matchesCompleted = $matchesRemaining === null ? 0 : (10 - $matchesRemaining);

        return match (true) {
            $rankClass > 0 && $rankClass < 6 => new Csr($value, $subTier, 'Bronze'),
            $rankClass >= 6 && $rankClass < 12 => new Csr($value, ($subTier - 6), 'Silver'),
            $rankClass >= 12 && $rankClass < 18 => new Csr($value, ($subTier - 12), 'Gold'),
            $rankClass >= 18 && $rankClass < 24 => new Csr($value, ($subTier - 18), 'Platinum'),
            $rankClass >= 24 && $rankClass < 30 => new Csr($value, ($subTier - 24), 'Diamond'),
            $rankClass >= 30 => new Csr($value, null, 'Onyx'),
            default => new Csr(0, $matchesCompleted, 'Unranked'),
        };
    }
}
