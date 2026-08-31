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
        stepping: 15,
        useCurrent: false,
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
        stepping: 15,
        useCurrent: false,
    });
    if (endInput.value) {
        end.dates.setValue(end.dates.parseInput(endInput.value));
    }

    startInput.addEventListener(Namespace.events.change, (e) => {
        end.updateOptions({
            restrictions: {
                minDate: e.detail.date,
            },
        });
    });
});
