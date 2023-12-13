document.addEventListener('livewire:init', () => {
    parseLocalDates();

    Livewire.hook('element.updated', () => parseLocalDates());
});

function parseLocalDates()
{
    const $dates = Array.prototype.slice.call(document.querySelectorAll('.local-date'), 0);

    if ($dates.length > 0) {
        $dates.forEach(el => {
            const date = new Date(el.getAttribute('datetime'));
            el.innerHTML = date.toDateString() + '&nbsp;' + date.toLocaleTimeString();
        });
    }
}
