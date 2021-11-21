<?php

namespace Tests\Feature\Forms\AddGamer;

use App\Http\Livewire\AddGamerForm;
use Livewire\Livewire;
use Tests\TestCase;

class InvalidGamerFormTest extends TestCase
{
    /** @dataProvider dataProvider */
    public function testInvalidTestSubmitted(?string $gamertag, string $validationError): void
    {
        Livewire::test(AddGamerForm::class)
            ->set('gamertag', $gamertag)
            ->call('submit')
            ->assertHasErrors([
                'gamertag' => $validationError
            ]);
    }

    public function dataProvider(): array
    {
        return [
            'empty' => [
                'gamertag' => null,
                'validation' => 'required'
            ],
            'too long' => [
                'gamertag' => 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnoprstuvwxyz',
                'validation' => 'max'
            ]
        ];
    }
}
