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
        return $this->queue->description ?? '';
    }

    public function getIconAttribute(): ? string
    {
        if ($this->queue && $this->input && $this->queue->is(Queue::SOLO_DUO)) {
            return $this->input->is(Input::CONTROLLER())
                ? '<i class="ml-1 fa fa-gamepad"></i>'
                : '<i class="ml-1 fa fa-mouse"></i>';
        }

        return null;
    }

    public function setQueueAttribute(?string $value): void
    {
        if (is_null($value)) {
            return;
        }

        $queue = is_numeric($value) ? Queue::fromValue((int) $value) : Queue::coerce($value);
        if (empty($queue)) {
            throw new \InvalidArgumentException('Invalid Queue Enum (' . $value . ')');
        }

        $this->attributes['queue'] = $queue->value;
    }

    public function setInputAttribute(?string $value): void
    {
        if (is_null($value)) {
            return;
        }

        $input = is_numeric($value) ? Input::fromValue((int) $value) : Input::coerce($value);
        if (empty($input)) {
            throw new \InvalidArgumentException('Invalid Input Enum (' . $value . ')');
        }

        $this->attributes['input'] = $input->value;
    }
}
