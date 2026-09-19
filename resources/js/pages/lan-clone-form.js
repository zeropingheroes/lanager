import {createApp} from 'vue';
import {i18nVue} from 'laravel-vue-i18n';
import LanCloneItemSelection from '../components/lans/lan-clone-item-selection.vue';

const selectionApp = document.getElementById('lan-clone-item-selection-app');
if (selectionApp) {
    const app = createApp({});
    app.use(i18nVue, {
        resolve: async lang => {
            const langs = import.meta.glob('../../lang/*.json');
            return await langs[`../../lang/php_${lang}.json`]();
        }
    });
    app.component('LanCloneItemSelection', LanCloneItemSelection);
    app.mount(selectionApp);
}

document.addEventListener('DOMContentLoaded', function () {
    const startInput = document.getElementById('start');
    const endInput = document.getElementById('end');

    if (!startInput || !endInput) {
        return;
    }

    const start = new TempusDominus(startInput, {
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
        stepping: 15,
        useCurrent: false,
    });
    if (startInput.value) {
        start.dates.setValue(start.dates.parseInput(startInput.value));
    }

    const end = new TempusDominus(endInput, {
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
