@extends ('layouts.app')

@section('subtitle', 'Payer pour les événements')

@section('content_header_title', 'Payer pour les événements')

@section('content')
<div class="container">
    <h3>Événements disponibles pour vos enfants</h3>
    <div id="events-container">
        <!-- Les événements seront chargés ici -->
    </div>
    <button class="btn btn-primary" onclick="submitPayment()">Payer</button>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", async function() {

        await getCsrfToken(); // Initialize CSRF protection
        
        loadAvailableEvents();

        async function loadAvailableEvents() {
            const userId = '{{ Auth::id() }}';
            try {
                const response = await fetch(`/api/events/available/${userId}`, {
                    credentials: 'include', // Include authentication cookies
                    headers: {
                        'Accept': 'application/json',
                        'content-type': 'application/json'
                    }
                });
                if (!response.ok) {
                    throw new Error('Error loading events');
                }
                const data = await response.json();
                displayEvents(data);
            } catch (error) {
                console.error('Error loading events:', error);
            }
                
            
        }

        function displayEvents(events) {
            const container = document.getElementById('events-container');
            container.innerHTML = ''; // Clear previous entries

            events.forEach(event => {
                const box = document.createElement('div');
                box.className = 'event-entry';
                box.innerHTML = `
                    <div class="event-details">
                        <strong>${event.title}</strong> - ${new Date(event.schedule).toLocaleDateString()}
                        <input type="checkbox" class="event-checkbox" value="${event.id}">
                    </div>
                `;
                container.appendChild(box);
            });
        }

        window.submitPayment = async function() {
            const selectedEvents = Array.from(document.querySelectorAll('.event-checkbox:checked')).map(checkbox => checkbox.value);
            if (selectedEvents.length === 0) {
                alert('Veuillez sélectionner au moins un événement.');
                return;
            }

            const userId = '{{ Auth::id() }}';
            try {
                const response = await fetch('/api/payments', {
                    method: 'POST',
                    credentials: 'include', // Include authentication cookies
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        userId,
                        events: selectedEvents
                    })
                });
                if (!response.ok) {
                    throw new Error('Error submitting payment');
                }
                const data = await response.json();
                alert('Paiement effectué avec succès!');
                loadAvailableEvents(); // Reload the events
            } catch (error) {
                console.error('Error submitting payment:', error);
                alert('Erreur lors de l\'enregistrement du paiement');
            }
        }
    });
</script>
@endpush