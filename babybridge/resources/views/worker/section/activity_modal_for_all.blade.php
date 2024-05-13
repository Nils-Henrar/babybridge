<!-- Modal pour ajouter une activité pour tous les enfants présents -->
<div class="modal fade" id="activityModalForAll" tabindex="-1" role="dialog" aria-labelledby="activityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activityModalLabel">Ajouter une activité pour tous les enfants présents</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="activityFormForAll">
                    <div class="form-group">
                        <label for="activity_description_all">Description de l'activité</label>
                        <select class="form-control" id="activity_description_all" required>
                            <!-- Les options seront chargées ici -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="activity_time_all">Heure de l'activité</label>
                        <input type="time" class="form-control" id="activity_time_all" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer pour tous</button>
                </form>
            </div>
        </div>
    </div>
</div>
