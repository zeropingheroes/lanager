<script setup>
import {reactive, watch, computed} from 'vue';
import FullCalendar from '@fullcalendar/vue3';
import timeGridPlugin from '@fullcalendar/timegrid';
import bootstrap5Plugin from '@fullcalendar/bootstrap5';
import {trans, isLoaded} from 'laravel-vue-i18n';

const props = defineProps(['lan_id']);

const apiBaseUrl = document.head.querySelector('meta[name="api-base-url"]').content;

const baseCalendarOptions = reactive({
    plugins: [timeGridPlugin, bootstrap5Plugin],
    themeSystem: 'bootstrap5',
    initialView: 'timeGridThreeDay',
    views: {
        timeGridThreeDay: {
            type: 'timeGrid',
            duration: {days: 3}
        }
    },
    height: 'auto',
    stickyHeaderDates: false,
    allDaySlot: false,
    nowIndicator: true,
    headerToolbar: false,
    footerToolbar: {
        start: '',
        center: '',
        end: 'today prev,next',
    },
    buttonIcons: false,
    eventColor: '#157800',
    eventTextColor: '#fff',
    eventBorderColor: '#157800',
    eventSourceSuccess: function (content, response) {
        return content.data;
    },
    eventDataTransform(event) {
        return {
            id: event.id,
            title: event.name,
            start: event.start,
            end: event.end,
            url: event.links.self_gui,
        }
    },
    events: `${apiBaseUrl}/lans/${props.lan_id}/events/`,
});

// Create a computed property for buttonText
const buttonText = computed(() => ({
    today: trans('phrase.today'),
    month: trans('phrase.month'),
    week: trans('phrase.week'),
    day: trans('phrase.day'),
    list: trans('phrase.list'),
    next: '>',
    prev: '<',
}));

const calendarOptions = computed(() => ({
    ...baseCalendarOptions,
    buttonText: buttonText.value
}));

watch(isLoaded, (loaded) => {
    if (loaded) {
        buttonText.value;
        calendarOptions.value;
    }
}, {immediate: true});
</script>

<template>
    <FullCalendar :options="calendarOptions"/>
</template>

<style>
.fc-timegrid-event {
    box-shadow: 0 0 0 1px #000 !important;
}

.fc-theme-bootstrap5 td,
.fc-theme-bootstrap5 th,
.fc-theme-bootstrap5 .fc-scrollgrid {
    border-color: #444;
}

.fc .fc-timegrid-col.fc-day-today {
    background-color: rgb(0 171 4 / 30%);
}
</style>
