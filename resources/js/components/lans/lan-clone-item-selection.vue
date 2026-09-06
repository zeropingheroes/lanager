<script setup>
import {ref, onMounted, computed} from 'vue';
import moment from 'moment';
import LanCloneItemSelectionTable from './lan-clone-item-selection-table.vue';
import LanCloneItemSelectionRowLink from './lan-clone-item-selection-row-link.vue';
import LanCloneItemSelectionRowWarning from './lan-clone-item-selection-row-warning.vue';


const props = defineProps({
    sourceLan: {type: Object, required: true},
    guides: {type: Array, default: () => []},
    events: {type: Array, default: () => []},
    slides: {type: Array, default: () => []},
    webhooks: {type: Array, default: () => []},
});

const selectedGuideIds = ref(new Set(props.guides.map(guide => guide.id)));
const selectedEventIds = ref(new Set(props.events.map(event => event.id)));
const selectedSlideIds = ref(new Set(props.slides.map(slide => slide.id)));
const selectedWebhookIds = ref(new Set(props.webhooks.map(webhook => webhook.id)));

const newLanStart = ref(null);
const newLanEnd = ref(null);

const PICKER_FORMAT = 'YYYY-MM-DD HH:mm';

function parsePickerDateTime(value) {
    return value ? moment(value, PICKER_FORMAT) : null;
}

function itemUrl(type, item) { return `/lans/${props.sourceLan.id}/${type}/${item.id}`; }

function itemExcerpt(item, field, length = 80) {
    const text = item[field]
    if (!text) return '';
    return text.length > length ? `${text.slice(0, length)}...` : text }

function itemFormattedDateTime(item, field) {
    const shiftedItem = shiftStartAndEndDates(item);
    return shiftedItem ? shiftedItem[field].format('ddd HH:mm') : '';
}

// Move each item's date-time to the new LAN's dates, but preserve the item's time of day.
// Mirrors CloneLanService::shiftDate()
function shiftStartAndEndDates(item) {
    if (!item.start || !item.end || !newLanStart.value) {
        return null;
    }

    const shiftDate = dateTime => {
        const dayOffset = moment(dateTime).startOf('day').diff(moment(props.sourceLan.start).startOf('day'), 'days');
        const timeOfDay = moment(dateTime);

        return newLanStart.value.clone().startOf('day').add(dayOffset, 'days')
            .set({hour: timeOfDay.hour(), minute: timeOfDay.minute(), second: timeOfDay.second()});
    };

    return {
        start: shiftDate(item.start),
        end: shiftDate(item.end),
    };
}

function isOutOfLanTimeRange(item) {
    const shiftedItem = shiftStartAndEndDates(item);

    if (!shiftedItem || !newLanStart.value || !newLanEnd.value) {
        return false;
    }

    return shiftedItem.start.isBefore(newLanStart.value) || shiftedItem.end.isAfter(newLanEnd.value);
}

const anyItemOutOfTimeRange = computed(() => {
    return props.events.some(event => isOutOfLanTimeRange(event)) || props.slides.some(slide => isOutOfLanTimeRange(slide));
});

onMounted(() => {
    const startInput = document.getElementById('start');
    const endInput = document.getElementById('end');

    if (!startInput || !endInput) {
        return;
    }

    newLanStart.value = parsePickerDateTime(startInput.value);
    newLanEnd.value = parsePickerDateTime(endInput.value);

    const sourceDuration = moment(props.sourceLan.end).diff(props.sourceLan.start, 'seconds');

    // When the organiser sets the "start" field, and the "end" field is empty (or was automatically filled before)
    // automatically set the "end" field to make the new LAN the same duration as the source LAN
    let lastAutoFilledEndValue = null;
    startInput.addEventListener(window.Namespace.events.change, () => {
        newLanStart.value = parsePickerDateTime(startInput.value);

        if (newLanStart.value && (endInput.value === '' || endInput.value === lastAutoFilledEndValue)) {
            endInput.value = newLanStart.value.clone().add(sourceDuration, 'seconds').format(PICKER_FORMAT);
            endInput.dispatchEvent(new Event('change'));
            lastAutoFilledEndValue = endInput.value;
        }
    });
    endInput.addEventListener(window.Namespace.events.change, () => {
        newLanEnd.value = parsePickerDateTime(endInput.value);
    });
});
</script>

<template>
    <LanCloneItemSelectionTable type="guides"
                                title-key="title.guides"
                                field="guide_ids"
                                :items="guides"
                                v-model="selectedGuideIds"
    >
        <template #header>
            <th>{{ $t('title.title') }}</th>
            <th>{{ $t('title.link') }}</th>
            <th>{{ $t('title.content') }}</th>
        </template>
        <template #default="{ item }">
            <td>
                {{ item.title }}
            </td>
            <td class="text-center narrow-column">
                <LanCloneItemSelectionRowLink :href="itemUrl('guides', item)"/>
            </td>
            <td class="text-muted">
                {{ itemExcerpt(item, 'content') }}
            </td>
        </template>
    </LanCloneItemSelectionTable>

    <LanCloneItemSelectionTable type="events"
                                title-key="title.events"
                                field="event_ids"
                                :items="events"
                                v-model="selectedEventIds"
    >
        <template #header>
            <th>{{ $t('title.name') }}</th>
            <th>{{ $t('title.link') }}</th>
            <th>{{ $t('title.description') }}</th>
            <th class="text-end">{{ $t('title.start') }}</th>
            <th class="text-end">{{ $t('title.end') }}</th>
        </template>
        <template #default="{ item }">
            <td>
                {{ item.name }}
            </td>
            <td class="text-center narrow-column">
                <LanCloneItemSelectionRowLink :href="itemUrl('events', item)"/>
            </td>
            <td class="text-muted">
                {{ itemExcerpt(item, 'description') }}
            </td>
            <td class="text-end">
                <LanCloneItemSelectionRowWarning :visible="isOutOfLanTimeRange(item)"
                                                 :item-id="item.id"
                />
                {{ itemFormattedDateTime(item, 'start') }}
            </td>
            <td class="text-end">
                {{ itemFormattedDateTime(item, 'end') }}
            </td>
        </template>
    </LanCloneItemSelectionTable>

    <LanCloneItemSelectionTable type="slides"
                                title-key="title.slides"
                                field="slide_ids"
                                :items="slides"
                                v-model="selectedSlideIds"
    >
        <template #header>
            <th>{{ $t('title.name') }}</th>
            <th>{{ $t('title.link') }}</th>
            <th>{{ $t('title.content') }}</th>
            <th class="text-end">{{ $t('title.start') }}</th>
            <th class="text-end">{{ $t('title.end') }}</th>
        </template>
        <template #default="{ item }">
            <td>
                {{ item.name }}
            </td>
            <td class="text-center narrow-column">
                <LanCloneItemSelectionRowLink :href="itemUrl('slides', item)"/>
            </td>
            <td class="text-muted">
                {{ itemExcerpt(item, 'content') }}
            </td>
            <td class="text-end">
                <LanCloneItemSelectionRowWarning :visible="isOutOfLanTimeRange(item)"
                                                 :item-id="item.id"
                />
                {{ itemFormattedDateTime(item, 'start') }}
            </td>
            <td class="text-end">
                {{ itemFormattedDateTime(item, 'end') }}
            </td>
        </template>
    </LanCloneItemSelectionTable>

    <LanCloneItemSelectionTable type="webhooks"
                                title-key="title.discord-channel-webhooks"
                                field="webhook_ids"
                                :items="webhooks"
                                v-model="selectedWebhookIds"
    >
        <template #header>
            <th>{{ $t('title.name') }}</th>
        </template>
        <template #default="{ item }">
            <td>
                {{ item.purpose }}
            </td>
        </template>
    </LanCloneItemSelectionTable>

    <p v-if="anyItemOutOfTimeRange"
       class="text-warning"
       data-past-lan-end-summary
    >
        {{ $t('phrase.some-items-outside-of-lan-time-range') }}
    </p>
</template>
