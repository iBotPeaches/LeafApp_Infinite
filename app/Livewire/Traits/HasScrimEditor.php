<?php

namespace App\Livewire\Traits;

use App\Livewire\ScrimTogglePanel;

trait HasScrimEditor
{
    public bool $isScrimEditor = false;

    public array $scrimGameIds = [];

    public function toggleScrimMode(): void
    {
        $this->isScrimEditor = ! $this->isScrimEditor;
    }

    /** @codeCoverageIgnore */
    public function updatedScrimGameIds(): void
    {
        $this->dispatch('syncGameIds', $this->scrimGameIds)->to(ScrimTogglePanel::class);
    }
}
