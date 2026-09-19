document.addEventListener("DOMContentLoaded", function () {
    const startInput = document.getElementById('start');

    // Get the LAN data from embedded JSON (only present on "create" and "edit" pages)
    const lanDataElement = document.getElementById('lan-data');
    const lan = lanDataElement ? JSON.parse(lanDataElement.textContent) : null;

    let lanStart;
    let lanEnd;

    if (lan) {
        lanStart = new Date(lan.start);
        lanEnd = new Date(lan.end);
    }

    const lanRestrictions = {minDate: lanStart, maxDate: lanEnd};

    const start = new TempusDominus(startInput, {
        restrictions: lanRestrictions,
        localization: {
            format: 'yyyy-MM-dd HH:mm',
            hourCycle: 'h23',
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
        restrictions: lanRestrictions,
        localization: {
            format: 'yyyy-MM-dd HH:mm',
            hourCycle: 'h23',
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
    if (endInput.value) {
        end.dates.setValue(end.dates.parseInput(endInput.value));
    }

    startInput.addEventListener(Namespace.events.change, (e) => {
        // Default the end date-time picker's minimum date-time to the LAN's start date-time
        let endPickerMinDate = lanStart;

        // If the slide's start date-time is set, and it is later than the LAN's start date-time
        // Set the end picker's minimum date-time to the slide's start date-time
        if (e.detail.date) {
            const slideStartDate = new Date(e.detail.date);

            if (!lanStart || slideStartDate > lanStart) {
                endPickerMinDate = slideStartDate;
            }
        }

        end.updateOptions({
            restrictions: {
                minDate: endPickerMinDate,
                maxDate: lanEnd,
            },
        });
    });
});
