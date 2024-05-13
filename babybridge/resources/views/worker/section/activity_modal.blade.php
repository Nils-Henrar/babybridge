<!-- activity_modal.blade.php -->
<div class="modal fade" id="activityModal" tabindex="-1" role="dialog" aria-labelledby="activityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activityModalLabel">Ajouter/Modifier une activité</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="activityForm">
                    <input type="hidden" id="childIdInput" value="">
                    <div class="form-group">
                        <label for="activity_description">Description de l'activité</label>
                        <select class="form-control" id="activity_description" required>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="activity_time">Heure de l'activité</label>
                        <input type="time" class="form-control" id="activity_time" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>
