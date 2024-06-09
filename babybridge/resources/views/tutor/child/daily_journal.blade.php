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

    .entry-photo {
        margin-top: 10px;
    }

    .entry-photo img {
        width: 200px; /* Largeur fixe */
        height: 200px; /* Hauteur fixe */
        object-fit: cover; /* Conserve les proportions de l'image */
        border-radius: 10px;
        cursor: pointer;
    }

    .separator {
        height: 10px;
        background-color: #176FA1;
        margin: 10px 0;
        border-radius: 5px;
    }

    /* Styles pour le modal de prévisualisation des photos */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.8);
    }

    .modal-content {
        margin: 15% auto;
        padding: 20px;
        width: 80%;
        max-width: 700px;
        text-align: center;
        position: relative;
    }

    .modal-content img {
        width: 100%;
        height: auto;
    }

    .close {
        position: absolute;
        top: 10px;
        right: 20px;
        color: white;
        font-size: 30px;
        font-weight: bold;
        cursor: pointer;
    }

    .download-btn {
        margin-top: 20px;
        background-color: #176FA1;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .date-picker-container button {
        margin: 0 5px;
        padding: 5px 10px;
        font-size: 20px;
        border: none;
        color: #176FA1;
        background-color: #f4f6f9;
    }

    .date-picker-container input {
        text-align: center;
        font-size: 20px;
        border-radius: 15px;
        margin-bottom: 20px;
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

<!-- Modal de prévisualisation des photos -->
<div id="photoModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <img id="modalImage" src="" alt="Photo">
        <a id="downloadLink" href="" download>
            <button class="download-btn">Télécharger</button>
        </a>
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
        container.innerHTML = ''; // Efface les entrées précédentes

        data.forEach((entry, index) => {
            const box = document.createElement('div');
            box.className = 'journal-entry';
            box.innerHTML = `
                <div class="entry-time">${entry.time}</div>
                <div class="entry-content">
                    <strong>${entry.child_name}</strong> - ${entry.description}
                </div>
                ${entry.type === 'photo' ? `<div class="entry-photo"><img src="${entry.image_url}" alt="Photo de ${entry.child_name}" onclick="openModal('${entry.image_url}')"></div>` : ''}
            `;
            container.appendChild(box);

            // Ajoute un séparateur entre les entrées
            if (index < data.length - 1) {
                const separator = document.createElement('div');
                separator.className = 'separator';
                container.appendChild(separator);
            }
        });
    }

    function openModal(imageUrl) {
        const modal = document.getElementById('photoModal');
        const modalImage = document.getElementById('modalImage');
        const downloadLink = document.getElementById('downloadLink');

        modalImage.src = imageUrl;
        downloadLink.href = imageUrl;

        modal.style.display = "block";
    }

    function closeModal() {
        const modal = document.getElementById('photoModal');
        modal.style.display = "none";
    }

    // Fermer le modal en cliquant en dehors de l'image
    window.onclick = function(event) {
        const modal = document.getElementById('photoModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

@endpush
