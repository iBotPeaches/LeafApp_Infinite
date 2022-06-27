<div>
    <section class="card">
        <header class="card-header">
            <p class="card-header-title">
                Add Account
            </p>
        </header>
        <form wire:submit.prevent="submit" class="card-content">
            <div class="field has-addons">
                <div class="control is-expanded has-icons-left @error('gamertag') has-icons-right @enderror">
                    <input class="input @error('gamertag') is-danger @enderror" type="text" wire:model="gamertag" placeholder="Gamertag">
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
                <br />
            </div>
            <p class="help is-info" wire:loading>Searching Halo Infinite for your account.</p>
            @error('gamertag')<p class="help is-danger">The gamertag is invalid</p>@enderror
        </form>
    </section>
</div>
