document.addEventListener("DOMContentLoaded", function() {
    const datePickerElement = document.getElementById('date-picker');
    const datePicker = flatpickr(datePickerElement, {
        defaultDate: "today",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr, instance) {
            loadActivities(dateStr); // Load activities for the selected date
        }
    });

    document.getElementById('prev-day').addEventListener('click', () => {
        const currentDate = datePicker.selectedDates[0];
        const prevDate = new Date(currentDate.setDate(currentDate.getDate() - 1));
        datePicker.setDate(prevDate);
        loadActivities(datePickerElement.value);
    });

    document.getElementById('next-day').addEventListener('click', () => {
        const currentDate = datePicker.selectedDates[0];
        const nextDate = new Date(currentDate.setDate(currentDate.getDate() + 1));
        datePicker.setDate(nextDate);
        loadActivities(datePickerElement.value);
    });

    loadActivities(datePickerElement.value); // Initially load activities for the current date
});

async function loadActivities(date) {
    const sectionId = '{{ Auth::user()->worker->currentSection->section->id }}';
    const url = `/api/activities/section/${sectionId}/date/${date}`;
    try {
        const response = await fetch(url);
        const activities = await response.json();
        displayActivities(activities);
    } catch (error) {
        console.error('Error loading activities:', error);
    }
}

function displayActivities(activities) {
    const container = document.getElementById('activity-container');
    container.innerHTML = ''; // Clear the container
    activities.forEach(activity => {
        const activityHtml = `
            <div class="col-lg-12 col-6">
                <div class="small-box">
                    <div class="inner">
                        <h3>${activity.child.firstname} ${activity.child.lastname}</h3>
                        <p>Description: ${activity.description}</p>
                        <p>Performed at: ${new Date(activity.performed_at).toLocaleTimeString()}</p>
                        <button class="btn btn-primary" onclick="openActivityModal(${activity.child_id}, '${activity.description}', '${activity.performed_at}')">Edit Activity</button>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += activityHtml;
    });
}

function openActivityModal(childId, description, performedAt) {
    document.getElementById('childIdInput').value = childId;
    document.getElementById('activity_description').value = description;
    document.getElementById('activity_time').value = performedAt;
    $('#activityModal').modal('show');
}

document.getElementById('activityForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    const childId = document.getElementById('childIdInput').value;
    const description = document.getElementById('activity_description').value;
    const performedAt = document.getElementById('activity_time').value;
    const date = document.getElementById('date-picker').value;

    const data = {
        child_id: childId,
        description: description,
        performed_at: `${date} ${performedAt}` // Combine date with time for backend processing
    };

    try {
        const response = await fetch('/api/activities', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error('Failed to save activity');
        }
        const result = await response.json();
        console.log('Activity saved successfully:', result);
        alert('Activity saved successfully!');
        $('#activityModal').modal('hide');
        loadActivities(date); // Reload activities to update the display
    } catch (error) {
        console.error('Error saving activity:', error);
        alert('Error saving activity.');
    }
});
