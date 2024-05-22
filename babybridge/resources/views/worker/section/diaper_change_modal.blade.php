<!-- Exemple de modal -->
<div class="modal fade" id="diaperChangeModal" tabindex="-1" aria-labelledby="diaperChangeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="diaperChangeModalLabel">Modifier Changement de Couche</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="diaperChangeForm">
                    <input type="hidden" id="diaperChangeId">
                    <input type="hidden" id="childId">
                    <div class="mb-3">
                        <label for="poop_consistency" class="form-label">Consistance des selles</label>
                        <select id="poop_consistency" class="form-select">
                            <option value="">Sélectionnez la consistance des selles</option>
                            <option value="watery">Liquide</option>
                            <option value="soft">Mou</option>
                            <option value="normal">Normale</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea id="notes" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="diaper_time" class="form-label">Heure</label>
                        <input type="time" id="diaper_time" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="submitDiaperChangeForm()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>
