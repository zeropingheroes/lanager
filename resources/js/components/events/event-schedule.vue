<template>
    <div id="schedule">
        <full-calendar :events="events"
                       :config="config"
                       :header="header"
                       :event-sources="eventSources">
        </full-calendar>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                events: [],
                eventSources: [
                    {
                        events(start, end, timezone, callback) {
                            axios.get('events')
                                .then((response) => {
                                    callback(response.data.data);
                                }, (error) => {
                                    console.log('Error getting events')
                                })
                        }
                    }
                ],
                header: {
                    left: '',
                    center: '',
                    right: ' agendaDay agendaThreeDay agendaWeek today prev,next'
                },
                config: {
                    slotEventOverlap: false,
                    editable: false,
                    selectable: false,
                    allDaySlot: false,
                    defaultView: 'agendaThreeDay',
                    nowIndicator: true,
                    firstDay: 1,
                    theme: false,
                    height: "auto",
                    eventColor: '#157800',
                    eventTextColor: '#000',
                    eventDataTransform(event) {
                        return {
                            id: event.id,
                            title: event.name,
                            start: event.start,
                            end: event.end,
                            url: event.links.self_gui,
                        }
                    },
                    views: {
                        agendaThreeDay: {
                            type: 'agenda',
                            duration: { days: 3 },
                            buttonText: '3 day'
                        }
                    }
                }
            }
        }
    }
</script>
