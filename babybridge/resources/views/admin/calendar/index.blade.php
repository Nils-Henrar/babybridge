@extends('layouts.app')

@section('title', 'Calendrier')

@section('content_header')
<h1>Calendrier des événements</h1>
@stop

@section('content')
<div class="card card-primary">
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>
@stop

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.x/main.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                fetch('/api/events')
                    .then(response => response.json())
                    .then(data => {
                        var events = data.data.map(event => {
                            return {
                                id: event.id,
                                title: event.title,
                                start: event.start,
                                description: event.description
                            };
                        });
                        successCallback(events);
                    })
                    .catch(error => {
                        failureCallback(error);
                    });
            },
            eventContent: function(arg) {
                // Cela permet d'afficher plus de détails dans chaque événement du calendrier
                var title = document.createElement('div');
                title.innerHTML = `<b>${arg.event.title}</b>`; // Affiche le titre de l'événement

                var arrayOfDomNodes = [title];
                return {
                    domNodes: arrayOfDomNodes
                };
            }
        });
        calendar.render();
    });
</script>
@endpush