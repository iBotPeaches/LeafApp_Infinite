<?php
declare(strict_types=1);

namespace App\Models\Traits;

use App\Enums\Input;
use App\Enums\Queue;

/**
 * @property-read string $title
 * @property-read string $icon
 */
trait HasPlaylist
{
    public function getTitleAttribute(): string
    {
        return $this->queue->description;
    }

    public function getIconAttribute(): ? string
    {
        if ($this->queue->is(Queue::SOLO_DUO)) {
            return $this->input->is(Input::CONTROLLER())
                ? '<i class="ml-1 fa fa-gamepad"></i>'
                : '<i class="ml-1 fa fa-mouse"></i>';
        }

        return null;
    }
}
