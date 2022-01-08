<?php
declare(strict_types=1);

namespace App\Support\Csr;

class CsrHelper
{
    public static function getCsrFromValue(?int $value): Csr
    {
        // Since there is 50 CSR per level and 6 levels per class
        $rankClass = $value / 50;
        $subTier = ceil($rankClass + 0.001);

        switch (true) {
            case $rankClass > 0 && $rankClass < 6:
                return new Csr($value, 'Bronze ' . $subTier);

            case $rankClass >= 6 && $rankClass < 12:
                return new Csr($value, 'Silver ' . ($subTier - 6));

            case $rankClass >= 12 && $rankClass < 18:
                return new Csr($value, 'Gold ' . ($subTier - 12));

            case $rankClass >= 18 && $rankClass < 24:
                return new Csr($value, 'Platinum ' . ($subTier - 18));

            case $rankClass >= 24 && $rankClass < 30:
                return new Csr($value, 'Diamond ' . ($subTier - 24));

            case $rankClass >= 30:
                return new Csr($value, 'Onyx');

            default:
                return new Csr(0, 'Unranked');
        }
    }
}
