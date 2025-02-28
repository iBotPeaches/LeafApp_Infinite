<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Player;
use App\Rules\ValidInfiniteAccount;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

class AddGamerForm extends Component
{
    // @phpstan-ignore-next-line
    public $gamertag;

    public bool $isNav;

    protected array $rules = [
        'gamertag' => [
            'required',
            'min:1',
            'max:32',
        ],
    ];

    public function mount(bool $isNav = false): void
    {
        $this->isNav = $isNav;
    }

    public function updatedGamertag(?string $value): void
    {
        $this->resetValidation('gamertag');
    }

    public function submit(): ?Redirector
    {
        $this->validate();

        $player = Player::fromGamertag((string) $this->gamertag);
        if ($player->exists) {
            return $this->redirectPlayer($player);
        }

        $this->validate([
            'gamertag' => [
                new ValidInfiniteAccount,
            ],
        ]);

        $player = Player::fromGamertag((string) $this->gamertag);

        return $this->redirectPlayer($player);
    }

    public function render(): View
    {
        return view('livewire.add-gamer-form');
    }

    private function redirectPlayer(Player $player): Redirector
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return redirect()->route('player', [$player]); // @phpstan-ignore-line
    }
}
