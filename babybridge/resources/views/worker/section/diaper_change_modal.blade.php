<!-- Modal for Adding/Updating Diaper Changes -->
<div class="modal fade" id="diaperChangeModal" tabindex="-1" role="dialog" aria-labelledby="diaperChangeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="diaperChangeModalLabel">Ajouter/Modifier un changement de couche</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="diaperChangeForm">
                    <input type="hidden" id="childIdInput" name="child_id">
                    <div class="form-group">
                        <label for="happened_at">Heure du changement</label>
                        <input type="time" class="form-control" id="diaper_time" name="time" required>
                    </div>
                    <div class="form-group">
                        <label for="poop_consistency">Consistance des selles</label>
                        <select class="form-control" id="poop_consistency" name="poop_consistency">
                            <option value="">Sélectionnez la consistance</option>
                            <option value="soft">Molle</option>
                            <option value="watery">Liquide</option>
                            <option value="normal">Normale</option>
                            <option value="">Aucune selle</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="submitDiaperChangeForm()">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>
