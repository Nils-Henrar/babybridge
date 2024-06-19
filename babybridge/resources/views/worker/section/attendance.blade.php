@extends('layouts.app')

@section('subtitle', 'Présences')

@section('content_header_title', 'Enfants')

@section('content_header_subtitle', 'Présences')

@section('extra-css')
<style>
    .small-box {
        background-color: #f0f0f0;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 35px;
        color: #176FA1;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .attendance-inner {
        display: flex;
        align-items: center;
        width: 100%;
    }

    .child-photo img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
    }

    .child-info {
        margin-left: 15px;
    }

    .attendance-details {
        display: flex;
        align-items: center;
        margin-left: auto;
    }

    .time-input-group {
        display: flex;
        align-items: center;
        margin-left: 20px;
    }

    .time-label {
        font-size: 1.2rem;
        color: #176FA1;
        margin-right: 10px;
    }

    .timepicker {
        width: 60px;
        text-align: center;
        font-size: 1.2rem;
        border-radius: 15px;
        cursor: pointer;
        margin-right: 10px;
        border: 2px solid #176FA1;
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

    .title-section {
        font-size: 2rem;
        font-weight: bold;
        color: #176FA1;
        margin-bottom: 30px;
        text-align: center;
    }

    .presence-indicator {
        font-size: 1.5rem;
        margin-left: 10px;
    }

    .present {
        color: green;
    }

    .absent {
        color: red;
    }
</style>
@endsection

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
    <div id="loading" style="display: none; justify-content: center; align-items: center; height: 100vh;">
        <div>Chargement en cours...</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    async function getCsrfToken() {
        await fetch('/sanctum/csrf-cookie', {
            credentials: 'include' // Important pour envoyer les cookies
        });
    }

    async function loadChildrenAndAttendances(date) {
        document.getElementById('loading').style.display = 'flex';
        let sectionId = '{{ Auth::user()->worker->currentSection->section->id }}';

        try {
            const childrenResponse = await fetch(`/api/children/section/${sectionId}`, {
                credentials: 'include', // Include cookies with the request
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            const childrenData = await childrenResponse.json();

            const attendancesResponse = await fetch(`/api/attendances/section/${sectionId}/date/${date}`, {
                credentials: 'include', // Include cookies with the request
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            const attendancesData = await attendancesResponse.json();

            displayChildrenWithAttendances(childrenData, attendancesData);
            document.getElementById('loading').style.display = 'none';
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('loading').style.display = 'none';
        }
    }

    document.addEventListener("DOMContentLoaded", async function() { 
        await getCsrfToken();

        const datePickerElement = document.getElementById('date-picker');
        const datePicker = flatpickr(datePickerElement, {
            defaultDate: "today",
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                loadChildrenAndAttendances(dateStr);
            }
        });

        document.getElementById('prev-day').addEventListener('click', () => {
            const currentDate = datePicker.selectedDates[0];
            const prevDate = new Date(currentDate.setDate(currentDate.getDate() - 1));
            datePicker.setDate(prevDate);
            loadChildrenAndAttendances(datePickerElement.value);
        });

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
        container.innerHTML = '';

        children.forEach(child => {
            const attendance = attendances.find(a => a.child_id === child.id) || {};
            const isPresent = !!attendance.arrival_time;
            const hasDeparture = !!attendance.departure_time;

            let boxHtml = `
                <div class="col-lg-12 col-6">
                    <div class="small-box">
                        <div class="attendance-inner">
                            <div class="child-photo">
                                <img src="{{ asset('storage/${child.photo_path}') }}" alt="Photo de profil de ${child.firstname}">
                            </div>
                            <div class="child-info">
                                <h3>${child.firstname}</h3>
                                <p>${child.lastname}</p>
                            </div>
                            <div class="attendance-details">
                                <button onclick="togglePresence(${child.id})" class="btn ${isPresent ? 'btn-danger' : 'btn-success'} presence-btn" data-child-id="${child.id}">
                                    ${isPresent ? 'Absent' : 'Présent'}
                                </button>
                                <div class="time-input-group">
                                    <label for="arrival-${child.id}" class="time-label">Arrivée</label>
                                    <input type="text" value="${attendance.arrival_time || ''}" id="arrival-${child.id}" class="timepicker arrival-time" data-child-id="${child.id}" ${isPresent ? '' : 'disabled'}>
                                </div>
                                <div class="time-input-group">
                                    <label for="departure-${child.id}" class="time-label">Départ</label>
                                    <input type="text" value="${attendance.departure_time || ''}" id="departure-${child.id}" class="timepicker departure-time" data-child-id="${child.id}" ${isPresent ? '' : 'disabled'}>
                                    ${isPresent ? `<button onclick="recordDeparture(${child.id})" class="btn btn-warning departure-btn" data-child-id="${child.id}">${hasDeparture ? 'Annuler' : 'Départ'}</button>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            container.innerHTML += boxHtml;
        });

        flatpickr(".timepicker", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            onChange: function(selectedDates, dateStr, instance) {
                const inputElement = instance.element;
                const childId = inputElement.dataset.childId;
                const timeType = inputElement.classList.contains('arrival-time') ? 'arrival' : 'departure';
                updateAttendance(childId, dateStr, timeType);
            }
        });
    }

    async function togglePresence(childId) {
        const presenceBtn = document.querySelector(`.presence-btn[data-child-id="${childId}"]`);
        const arrivalInput = document.getElementById(`arrival-${childId}`);
        const departureInput = document.getElementById(`departure-${childId}`);
        const date = document.getElementById('date-picker').value;

        if (presenceBtn.classList.contains('btn-danger')) {
            // Suppression de la présence
            try {
                const response = await fetch(`/api/attendances/${childId}/${date}`, {
                    method: 'DELETE',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to delete attendance');
                }

                arrivalInput.value = '';
                departureInput.value = '';
                arrivalInput.disabled = true;
                departureInput.disabled = true;
                presenceBtn.classList.remove('btn-danger');
                presenceBtn.classList.add('btn-success');
                presenceBtn.innerText = 'Présent';
                // Remove the "Départ" button
                const departureBtn = document.querySelector(`.departure-btn[data-child-id="${childId}"]`);
                if (departureBtn) {
                    departureBtn.remove();
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error deleting attendance');
            }
        } else {
            // Ajout de la présence
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const time = `${hours}:${minutes}`;

            arrivalInput.value = time;
            arrivalInput.disabled = false;
            departureInput.disabled = false;

            const data = {
                child_id: childId,
                arrival_time: time,
                attendance_date: date
            };

            try {
                const response = await fetch('/api/attendances', {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    throw new Error('Failed to add attendance');
                }

                presenceBtn.classList.remove('btn-success');
                presenceBtn.classList.add('btn-danger');
                presenceBtn.innerText = 'Absent';

                // Add the "Départ" button next to the departure input
                const departureBtnHtml = `<button onclick="recordDeparture(${childId})" class="btn btn-warning departure-btn" data-child-id="${childId}">Départ</button>`;
                departureInput.insertAdjacentHTML('afterend', departureBtnHtml);
            } catch (error) {
                console.error('Error:', error);
                alert('Error adding attendance');
            }
        }
    }

    async function updateAttendance(childId, time = null, type = 'arrival') {
        const datePicker = document.getElementById('date-picker');
        const attendanceDate = datePicker.value;

        let arrivalTime, departureTime;
        if (type === 'arrival') {
            arrivalTime = time || document.getElementById(`arrival-${childId}`).value;
            departureTime = document.getElementById(`departure-${childId}`).value;
        } else {
            arrivalTime = document.getElementById(`arrival-${childId}`).value;
            departureTime = time || document.getElementById(`departure-${childId}`).value;
        }

        if (!arrivalTime) {
            alert('Arrival time must be set.');
            return;
        }

        const data = {
            child_id: childId,
            arrival_time: arrivalTime,
            departure_time: departureTime,
            attendance_date: attendanceDate
        };

        try {
            const response = await fetch('/api/attendances', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });
            if (!response.ok) {
                throw new Error('Failed to update attendance');
            }
            loadChildrenAndAttendances(attendanceDate); // Reload data after updating
        } catch (error) {
            console.error('Error:', error);
            alert('Error updating attendance');
        }
    }

    async function recordDeparture(childId) {
        const departureBtn = document.querySelector(`.departure-btn[data-child-id="${childId}"]`);
        const departureInput = document.getElementById(`departure-${childId}`);

        if (departureBtn.innerText === 'Départ') {
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const time = `${hours}:${minutes}`;

            departureInput.value = time;
            updateAttendance(childId, time, 'departure');
            departureBtn.innerText = 'Annuler';
        } else {
            departureInput.value = '';
            updateAttendance(childId, '', 'departure');
            departureBtn.innerText = 'Départ';
        }
    }
</script>
@endpush
