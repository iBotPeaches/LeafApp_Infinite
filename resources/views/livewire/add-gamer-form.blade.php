<div>
    @if ($isNav)
        <form wire:submit="submit" class="m-0">
            <div class="field m-0">
                <div
                    class="control has-icons-left has-icons-right"
                    wire:loading.class="is-loading"
                    wire:loading.class.remove="@error('gamertag') is-loading @enderror"
                >
                    <input
                        class="input @error('gamertag') is-danger @enderror"
                        type="text"
                        wire:model.live="gamertag"
                        placeholder="Gamertag"
                    >
                    <span class="icon is-small is-left">
                        <i class="fab fa-xbox"></i>
                    </span>
                    @error('gamertag')
                        <span class="icon is-small is-right has-text-danger">
                            <i class="fas fa-exclamation-triangle" title="The gamertag is invalid"></i>
                        </span>
                    @enderror
                </div>
            </div>
        </form>
    @else
        <section class="card">
            <header class="card-header">
                    <p class="card-header-title">
                    Add Account
                </p>
            </header>
            <form wire:submit="submit" class="card-content">
                <div class="field has-addons">
                    <div class="control is-expanded has-icons-left @error('gamertag') has-icons-right @enderror">
                        <input class="input @error('gamertag') is-danger @enderror" type="text" wire:model.live="gamertag" placeholder="Gamertag">
                        <span class="icon is-small is-left">
                            <i class="fab fa-xbox"></i>
                        </span>
                        @error('gamertag')
                            <span class="icon is-small is-right">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                        @enderror
                    </div>
                    <div class="control">
                        <button class="button is-link">Find Me</button>
                    </div>
                </div>
                <p class="help is-info" wire:loading>Searching Halo Infinite for your account.</p>
                @error('gamertag')<p class="help is-danger">The gamertag is invalid</p>@enderror
            </form>
        </section>
    @endif
</div>
