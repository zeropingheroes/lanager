import moment from 'moment';

document.addEventListener('DOMContentLoaded', function () {
    const lanSelect = document.getElementById('lan_id');
    const lansDataElement = document.getElementById('clone-form-lans');
    const startInput = document.getElementById('start');
    const endInput = document.getElementById('end');
    const signupsOpenInput = document.getElementById('signups_open');
    const signupsCloseInput = document.getElementById('signups_close');
    const warning = document.getElementById('clone-form-out-of-range-warning');

    if (!lanSelect || !lansDataElement || !startInput || !endInput || !warning) {
        return;
    }

    const PICKER_FORMAT = 'YYYY-MM-DD HH:mm';

    const lansById = new Map(
        JSON.parse(lansDataElement.textContent).map(lan => [
            String(lan.id),
            {start: moment(lan.start), end: moment(lan.end)},
        ])
    );

    let previousLanId = lanSelect.value;

    function parsePickerDateTime(value) {
        return value ? moment(value, PICKER_FORMAT) : null;
    }

    // Move the given date-time onto the new LAN's start date by the same number of days it currently falls after
    // the previous LAN's start date, but preserve its time of day.
    function shiftDate(previousLanStart, newLanStart, dateTime) {
        const dayOffset = dateTime.clone().startOf('day').diff(previousLanStart.clone().startOf('day'), 'days');

        return newLanStart.clone().startOf('day').add(dayOffset, 'days')
            .set({hour: dateTime.hour(), minute: dateTime.minute(), second: dateTime.second()});
    }

    function shiftInputDate(input, previousLanStart, newLanStart) {
        const currentValue = parsePickerDateTime(input.value);

        if (!currentValue) {
            return;
        }

        input.value = shiftDate(previousLanStart, newLanStart, currentValue).format(PICKER_FORMAT);
        input.dispatchEvent(new Event('change'));
    }

    // Whenever startInput changes for any reason, shift endInput by the
    // same amount of time, so the gap between them stays the same.
    function preserveDuration(startInput, endInput) {
        let previousStart = parsePickerDateTime(startInput.value);

        startInput.addEventListener(Namespace.events.change, () => {
            const newStart = parsePickerDateTime(startInput.value);

            // If the start picker's value is invalid, don't change the end input.
            // This guards against update events with intermediate or incomplete values.
            if (!newStart) {
                return;
            }

            const currentEnd = parsePickerDateTime(endInput.value);

            if (previousStart && currentEnd) {
                const deltaSeconds = newStart.diff(previousStart, 'seconds');
                endInput.value = currentEnd.clone().add(deltaSeconds, 'seconds').format(PICKER_FORMAT);
                endInput.dispatchEvent(new Event('change'));
            }

            previousStart = newStart;
        });
    }

    preserveDuration(startInput, endInput);
    if (signupsOpenInput && signupsCloseInput) {
        preserveDuration(signupsOpenInput, signupsCloseInput);
    }

    function updateWarning() {
        const lan = lansById.get(lanSelect.value);
        const start = parsePickerDateTime(startInput.value);
        const end = parsePickerDateTime(endInput.value);

        const outOfRange = !!lan && !!start && !!end
            && (start.isBefore(lan.start) || end.isAfter(lan.end));

        warning.hidden = !outOfRange;
    }

    lanSelect.addEventListener('change', () => {
        const previousLan = lansById.get(previousLanId);
        const newLan = lansById.get(lanSelect.value);

        if (previousLan && newLan) {

            // Shift the event start date to the new LAN's start date, plus how many days into the LAN it was
            // If the event allows signups, shift the signup start as well
            // No need to shift the end times, as preserveDuration() handles that
            shiftInputDate(startInput, previousLan.start, newLan.start);
            if (signupsOpenInput) {
                shiftInputDate(signupsOpenInput, previousLan.start, newLan.start);
            }
        }

        previousLanId = lanSelect.value;
        updateWarning();
    });

    startInput.addEventListener(Namespace.events.change, updateWarning);
    endInput.addEventListener(Namespace.events.change, updateWarning);

    updateWarning();
});
