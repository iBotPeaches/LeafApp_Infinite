<div>
    <form wire:submit.prevent="submit">
        <div wire:loading>
            Searching Halo for your account...
        </div>
        <input type="text" wire:model="gamertag">
        @error('gamertag') <span class="error">{{ $message }}</span> @enderror

        <button type="submit">Find Me</button>
    </form>
</div>
