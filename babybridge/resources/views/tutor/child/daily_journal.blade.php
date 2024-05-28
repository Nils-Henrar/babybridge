@extends('layouts.app')

@section('subtitle', 'Daily Journal')

@section('content_header_title', 'Children')

@section('content_header_subtitle', 'Daily Journal')

@section('extra-css')
<style>
    .journal-entry {
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 10px;
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

    .separator {
        height: 10px;
        background-color: #176FA1;
        margin: 10px 0;
        border-radius: 5px;
    }
</style>
@endsection

@section('content_body')
<div class="container">
    <h3>Journal de bord de
        @foreach ($children as $key => $child)
        <strong>{{ $child->firstname }}</strong>
        @if ($loop->iteration < $loop->count - 1)
            {{ ',' }}
        @elseif ($loop->iteration == $loop->count - 1)
            {{ ' et' }}
        @endif
        @endforeach
    </h3>
    <div class="date-picker-container" style="text-align: center; margin-top: 20px;">
        <button id="prev-day"><i class="fas fa-arrow-left"></i></button>
        <input type="text" id="date-picker" class="form-control" style="display: inline-block; width: auto;">
        <button id="next-day"><i class="fas fa-arrow-right"></i></button>
    </div>
    <div id="daily-journal">
        <!-- Les informations journalières seront chargées ici -->
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", async function() {
        await getCsrfToken(); // Initialiser la protection CSRF

        const datePicker = flatpickr("#date-picker", {
            enableTime: false,
            dateFormat: "Y-m-d",
            defaultDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                loadJournalForDate(dateStr);
            }
        });

        // Charger le journal pour la date du jour au lancement de la page
        loadJournalForDate(datePicker.input.value);
    });

    async function getCsrfToken() {
        await fetch('/sanctum/csrf-cookie', {
            credentials: 'include' // Important pour envoyer les cookies
        });
    }

    async function loadJournalForDate(date) {
        const userId = '{{ Auth::id() }}';
        try {
            const response = await fetch(`/api/children/user/${userId}/daily-journal/${date}`, {
                credentials: 'include', // Inclure les cookies d'authentification
                headers: {
                    'Accept': 'application/json',
                    'content-type': 'application/json'
                }
            });
            if (!response.ok) {
                throw new Error('Erreur lors du chargement du journal quotidien');
            }
            const data = await response.json();
            displayJournal(data);
        } catch (error) {
            console.error('Error loading the daily journal:', error);
        }
    }

    function displayJournal(data) {
        const container = document.getElementById('daily-journal');
        container.innerHTML = ''; // Effacer les entrées précédentes

        data.forEach((entry, index) => {
            const box = document.createElement('div');
            box.className = 'journal-entry';
            box.innerHTML = `
                <div class="entry-time">${entry.time}</div>
                <div class="entry-content">
                    <strong>${entry.child_name}</strong> - ${entry.description}
                </div>
            `;
            container.appendChild(box);

            // Ajouter un séparateur entre les entrées
            if (index < data.length - 1) {
                const separator = document.createElement('div');
                separator.className = 'separator';
                container.appendChild(separator);
            }
        });
    }
</script>

@endpush
