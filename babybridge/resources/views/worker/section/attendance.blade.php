@extends ('layouts.app')

@section('subtitle', 'Présences')

@section('content_header_title', 'Enfants')

@section('content_header_subtitle', 'Présences')

@section('extra-css')
<style>
    .small-box {
        background-color: #D9D9D9;
        padding: 20px;
        margin-bottom: 20px; /* Ajoute un espace entre les boxes */
        align-items: center;
        border-radius: 50px; /* Arrondit les coins */
        color: #176FA1;
    }
    .attendance-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .attendance-time-input {
        margin: 0 10px; /* Espacement entre les champs de temps */
    }
    .save-btn {
        white-space: nowrap; /* Assure que le texte du bouton ne passe pas à la ligne */
    }
    .form-group {
    margin-bottom: 15px; /* Ajoute un espace sous chaque groupe de formulaire */
    }

    .small-box .inner {
        padding: 15px; /* Ajoute du padding à l'intérieur de chaque box pour espace les éléments */
    }

    .timepicker {
        width: 10%; /* Assure que les pickers prennent toute la largeur disponible */
        text-align: center; /* Centre le texte dans les champs de temps */
        font-size: 2rem; /* Taille de police normale */
        /* arrondir les bords */
        border-radius: 25px;
    }

    .time-label {
        font-size: 1.5rem; /* Taille de police normale */
        color: #176FA1; /* Couleur bleue */
    }

    .date-picker-container button {
    margin: 0 5px;
    padding: 5px 10px;
    font-size: 20px;
    background-color: #ffffff;
    border: none;
    color: #176FA1;
    }

    .date-picker-container input {
        text-align: center;
        font-size: 20px;
        border-radius: 25px;
        margin-bottom: 20px;
    }

    .fas fa-arrow-left {
        color: #176FA1;
    }

    .fas fa-arrow-right {
        background-color: #176FA1;
    }

    .title-section {
        font-size: 2rem;
        font-weight: bold;
        color: #176FA1;
        margin-bottom: 30px;
        text-align: center;
    }


</style>
@endsection

@section('content_body')

@section('content_body')
<div class="container">
    <div class="title-section">Section: {{ Auth::user()->worker->currentSection->section->name }}</div>



    <div class="date-picker-container" style="text-align: center; margin-top: 20px;">
        <button id="prev-day"><i class="fas fa-arrow-left"></i></button>
        <input type="text" id="date-picker" class="form-control" style="display: inline-block; width: auto;">
        <button id="next-day"><i class="fas fa-arrow-right"></i></button>
    </div>
    <div id="attendance-container" class="row">
        <!-- Les boîtes seront ajoutées ici par JavaScript -->
    </div>
    <!-- Élément de chargement -->
    <div id="loading" style="display: none; justify-content: center; align-items: center; height: 100vh;">
        <div>Chargement en cours...</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Assure que le script est exécuté après que le document soit entièrement chargé


async function loadChildrenAndAttendances(date) {
    document.getElementById('loading').style.display = 'flex'; // Afficher le loader
    let sectionId = '{{ Auth::user()->worker->currentSection->section->id }}' // ID de la section, assurez-vous qu'il est correctement défini
    try {
        const childrenResponse = await fetch(`/api/children/section/${sectionId}`);
        const childrenData = await childrenResponse.json();
        const attendancesResponse = await fetch(`/api/attendances/section/${sectionId}/date/${date}`);
        const attendancesData = await attendancesResponse.json();
        displayChildrenWithAttendances(childrenData, attendancesData);
        document.getElementById('loading').style.display = 'none'; // Masquer le loader
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('loading').style.display = 'none'; // Masquer le loader en cas d'erreur
    }
}



document.addEventListener("DOMContentLoaded", function() {
    const datePickerElement = document.getElementById('date-picker');
    const datePicker = flatpickr(datePickerElement, {
        defaultDate: "today",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr, instance) {
            console.log('Date after change in datePicker:', dateStr);
            loadChildrenAndAttendances(dateStr); // Charge les données pour la date sélectionnée
        }
    });

    // Bouton pour aller au jour précédent
    document.getElementById('prev-day').addEventListener('click', () => {
        const currentDate = datePicker.selectedDates[0];
        const prevDate = new Date(currentDate.setDate(currentDate.getDate() - 1));
        datePicker.setDate(prevDate);
        loadChildrenAndAttendances(datePickerElement.value);
    });

    // Bouton pour aller au jour suivant
    document.getElementById('next-day').addEventListener('click', () => {
        const currentDate = datePicker.selectedDates[0];
        const nextDate = new Date(currentDate.setDate(currentDate.getDate() + 1));
        datePicker.setDate(nextDate);
        loadChildrenAndAttendances(datePickerElement.value);
    });

    loadChildrenAndAttendances(datePickerElement.value);
});
function displayChildrenWithAttendances(children, attendances) {
    let container = document.getElementById('attendance-container');
    container.innerHTML = ''; // Clear previous contents if needed

    children.forEach(child => {
        const attendance = attendances.find(a => a.child_id === child.id) || {};
        let boxHtml = `
            <div class="col-lg-12 col-6">
                <div class="small-box">
                    <div class="inner">
                        <h3>${child.firstname} ${child.lastname}</h3>
                        <div class="form-group">
                            <label for="arrival-${child.id}" class="time-label">Heure d'arrivée</label>
                            <input type="text" value="${attendance.arrival_time || ''}" id="arrival-${child.id}" class="timepicker">
                        </div>
                        <div class="form-group">
                            <label for="departure-${child.id}" class="time-label">Heure de départ</label>
                            <input type="text" value="${attendance.departure_time || ''}" id="departure-${child.id}" class="timepicker">
                        </div>
                        <button onclick="updateAttendance(${child.id})" class="btn btn-success">Save</button>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += boxHtml;
    });

    // Initialisez Flatpickr
    flatpickr(".timepicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });
}


async function updateAttendance(childId) {
    const arrivalTime = document.getElementById(`arrival-${childId}`).value;
    const departureTime = document.getElementById(`departure-${childId}`).value;
    const attendance_date = document.getElementById('date-picker').value; // Utilisez directement la valeur du datePicker
    const data = {
        child_id: childId,
        arrival_time: arrivalTime,
        departure_time: departureTime,
        attendance_date: attendance_date // Utilise la date sélectionnée
    };

    console.log('Updating attendance:', data);

    try {
        const response = await fetch('/api/attendances', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });
        if (!response.ok) {
            throw new Error('Failed to update attendance');
        }
        alert('Attendance updated successfully');
    } catch (error) {
        console.error('Error:', error);
        alert('Error updating attendance');
    }
}

    
</script>
@endpush