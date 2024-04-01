<?php

declare(strict_types=1);

namespace App\Models\Traits;

trait HasPerformance
{
    public function getExpectedKillsColor(): string
    {
        if (is_null($this->expected_kills)) {
            return '';
        }

        return match (true) {
            $this->kills > $this->expected_kills => 'has-background-success-soft',
            $this->kills < $this->expected_kills => 'has-background-danger-soft',
            $this->kills === $this->expected_kills => 'has-background-primary-soft',
            default => ''
        };
    }

    public function getExpectedDeathsColor(): string
    {
        if (is_null($this->expected_deaths)) {
            return '';
        }

        return match (true) {
            $this->deaths > $this->expected_deaths => 'has-background-danger-soft',
            $this->deaths < $this->expected_deaths => 'has-background-success-soft',
            $this->deaths === $this->expected_deaths => 'has-background-primary-soft',
            default => ''
        };
    }
}
