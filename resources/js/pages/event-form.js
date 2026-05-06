document.addEventListener("DOMContentLoaded", function () {

    const startInput = document.getElementById('start');
    const start = new TempusDominus(startInput, {
        localization: {
            format: 'yyyy-MM-dd HH:mm',
            dayViewHeaderFormat: {month: 'long', year: 'numeric'},
        },
        display: {
            sideBySide: true,
            theme: "dark",
            buttons: {
                today: true,
                clear: true,
            },
        },
    });
    if (startInput.value) {
        start.dates.setValue(start.dates.parseInput(startInput.value));
    }

    const endInput = document.getElementById('end');
    const end = new TempusDominus(endInput, {
        localization: {
            format: 'yyyy-MM-dd HH:mm',
            dayViewHeaderFormat: {month: 'long', year: 'numeric'},
        },
        display: {
            sideBySide: true,
            theme: "dark",
            buttons: {
                today: true,
                clear: true,
            },
        },
        useCurrent: false,
    });
    if (endInput.value) {
        end.dates.setValue(end.dates.parseInput(endInput.value));
    }

    const signupsOpenInput = document.getElementById('signups_open');
    const signupsOpen = new TempusDominus(signupsOpenInput, {
        localization: {
            format: 'yyyy-MM-dd HH:mm',
            dayViewHeaderFormat: {month: 'long', year: 'numeric'},
        },
        display: {
            sideBySide: true,
            theme: "dark",
            buttons: {
                today: true,
                clear: true,
            },
        },
    });
    if (signupsOpenInput.value) {
        signupsOpen.dates.setValue(signupsOpen.dates.parseInput(signupsOpenInput.value));
    }

    const signupsCloseInput = document.getElementById('signups_close');
    const signupsClose = new TempusDominus(signupsCloseInput, {
        localization: {
            format: 'yyyy-MM-dd HH:mm',
            dayViewHeaderFormat: {month: 'long', year: 'numeric'},
        },
        display: {
            sideBySide: true,
            theme: "dark",
            buttons: {
                today: true,
                clear: true,
            },
        },
        useCurrent: false,
    });
    if (signupsCloseInput.value) {
        signupsClose.dates.setValue(signupsClose.dates.parseInput(signupsCloseInput.value));
    }

    startInput.addEventListener(Namespace.events.change, (e) => {
        end.updateOptions({
            restrictions: {
                minDate: e.detail.date,
            },
        });
        signupsOpen.updateOptions({
            restrictions: {
                maxDate: e.detail.date,
            },
        });
    });

    signupsOpenInput.addEventListener(Namespace.events.change, (e) => {
        signupsClose.updateOptions({
            restrictions: {
                minDate: e.detail.date,
            },
        });
    });

    const discordNotifyCheckbox = document.getElementById('discord_notify');
    const discordMessageRow = document.getElementById('discord_message_row');
    const discordMessageTextarea = document.getElementById('discord_message');
    const descriptionTextarea = document.getElementById('description');

    discordNotifyCheckbox.addEventListener('change', () => {
        if (discordNotifyCheckbox.checked) {
            if (!discordMessageTextarea.value.trim() && descriptionTextarea) {
                discordMessageTextarea.value = descriptionTextarea.value;
            }
            discordMessageRow.style.display = '';
        } else {
            discordMessageRow.style.display = 'none';
        }
    });
});
