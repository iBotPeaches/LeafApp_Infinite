<div class="notification mb-1">
    <div class="control has-icons-left">
        <div class="select is-fullwidth">
            <span class="icon is-large is-left">
                <i class="fas fa-box"></i>
            </span>
            <select wire:model="seasonKey" wire:change="onSeasonChange">
                @foreach ($seasons as $seasonModel)
                    <option value="{{ $seasonModel->key }}">
                        {{ $seasonModel->season_id }}-{{ $seasonModel->name }}
                    </option>
                @endforeach
                <option value="-1">All Seasons</option>
            </select>
        </div>
    </div>
    @if (in_array($type, ['overview', 'medals']))
        <div class="control has-icons-left mt-3">
            <div class="select is-fullwidth">
                <span class="icon is-large is-left">
                    <i class="fas fa-globe"></i>
                </span>
                <select wire:model="playerType" wire:change="onChange">
                    <option value="{{ App\Enums\Mode::MATCHMADE_RANKED }}">Ranked Only</option>
                    <option value="{{ App\Enums\Mode::MATCHMADE_PVP }}">All PVP</option>
                </select>
            </div>
        </div>
    @endif
</div>
