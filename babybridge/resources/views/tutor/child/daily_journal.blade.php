@extends('layouts.app')

@section('subtitle', 'Daily Journal')

@section('content_header_title', 'Children')

@section('content_header_subtitle', 'Daily Journal')


@section('extra-css')

<style>
    .journal-entry {
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 10px;
        background-color: #f9f9f9;
    }

    .entry-time {
        font-size: 0.9em;
        color: #666;
        margin-bottom: 5px;
    }

    .entry-content {
        font-size: 1.1em;
        color: #333;
    }
</style>

@endsection

@section('content_body')
<div class="container">
    <!-- et si il sont deux sinon  , et le dernier en et -->
    <h3>Journal de bord de
        @foreach ($children as $key => $child)
        <strong>{{ $child->firstname }}</strong>
        @if ($loop->iteration < $loop->count - 1)
            {{ ',' }}
            @elseif ($loop->iteration == $loop->count - 1)
            {{ ' et' }}
            @endif
            @endforeach
            <input type="text" id="date-picker" class="form-control">

            <div id="daily-journal">
                <!-- Les informations journalières seront chargées ici -->
            </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const datePicker = flatpickr("#date-picker", {
            enableTime: false,
            dateFormat: "Y-m-d",
            defaultDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                loadJournalForDate(dateStr);
            }
        });

        function loadJournalForDate(date) {
            const userId = '{{ Auth::id() }}';
            fetch(`/api/children/user/${userId}/daily-journal/${date}`)
                .then(response => response.json())
                .then(data => {
                    displayJournal(data);
                })
                .catch(error => console.error('Error loading the daily journal:', error));
        }

        function displayJournal(data) {
            const container = document.getElementById('daily-journal');
            container.innerHTML = ''; // Clear previous entries

            data.forEach(entry => {
                const box = document.createElement('div');
                box.className = 'journal-entry';
                box.innerHTML = `
                <div class="entry-time">${entry.time}</div>
                <div class="entry-content">
                    <strong>${entry.child_name}</strong> - ${entry.description}
                </div>
            `;
                container.appendChild(box);
            });
        }
    });
</script>
@endpush