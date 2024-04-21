<?php
    /** @var App\Models\OverviewMap[] $maps */
    /** @var App\Models\OverviewGametype $gametype */
?>
<div class="notification mb-1">
    <div class="control has-icons-left">
        <div class="select is-fullwidth">
            <span class="icon is-large is-left">
                <i class="fas fa-map"></i>
            </span>
            <select wire:model.live="mapId" wire:change="onMapChange">
                @foreach ($maps as $map)
                    <option value="{{ $map->id }}">
                        Version: {{ $map->released_at->format('Y-m-d') }}
                    </option>
                @endforeach
                <option value="-1">All Versions</option>
            </select>
        </div>
    </div>
    <div class="control has-icons-left mt-3">
        <div class="select is-fullwidth">
            <span class="icon is-large is-left">
                <i class="fas fa-gears"></i>
            </span>
            <select wire:model.live="gametypeId" wire:change="onGametypeChange">
                @foreach ($gametypes as $gametype)
                    <option value="{{ $gametype->id }}">
                        {{ $gametype->name }}
                    </option>
                @endforeach
                <option value="-1">All Modes</option>
            </select>
        </div>
    </div>
</div>
