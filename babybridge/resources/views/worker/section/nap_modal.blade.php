<!-- Nap Modal -->
<div class="modal fade" id="napModal" tabindex="-1" role="dialog" aria-labelledby="napModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="napModalLabel">Gérer la Sieste</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="napForm">
                    <input type="hidden" id="napId" name="napId"> <!-- pour les mises à jour -->
                    <div class="form-group">
                        <label for="started_at">Heure de début</label>
                        <input type="time" class="form-control" id="started_at" name="started_at">
                    </div>
                    <div class="form-group">
                        <label for="ended_at">Heure de fin</label>
                        <input type="time" class="form-control" id="ended_at" name="ended_at">
                    </div>
                    <div class="form-group">
                        <label for="quality">Qualité de la sieste</label>
                        <select class="form-control" id="quality" name="quality">
                            <option value="">Sélectionnez la qualité</option>
                            <option value="bad">Mauvaise</option>
                            <option value="average">Moyenne</option>
                            <option value="good">Bonne</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes (facultatif)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" onclick="submitNapForm()">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>
