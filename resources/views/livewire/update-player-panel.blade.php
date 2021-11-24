<article class="message is-small {{ $color }}" wire:init="processUpdate">
    <div class="message-header">
        <p>Checking for Update</p>
    </div>
    <div class="message-body">
        {{ $message }}
    </div>
</article>
