<template>
    <div id="schedule">
        <full-calendar :events="events"
                       :config="config"
                       :header="header"></full-calendar>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                events: [
                    {
                        title  : 'event1',
                        start  : '2010-01-01',
                    },
                    {
                        title  : 'event2',
                        start  : '2010-01-05',
                        end    : '2010-01-07',
                    },
                    {
                        title  : 'event3',
                        start  : '2010-01-09T12:30:00',
                        allDay : false,
                    },
                ],
                header: {
                    left: '',
                    center: '',
                    right: ' agendaDay agendaWeek today prev,next'
                },
                config: {
                    slotEventOverlap: false,
                    editable: false,
                    selectable: false,
                    allDaySlot: false, // TODO: implement all day events in database
                    defaultView: 'agendaDay',
                    nowIndicator: true,
                    firstDay: 1,
                    theme: false,
                    height: "auto",
                    eventColor: "#0f6c00",
                }
            }
        }
    }
</script>