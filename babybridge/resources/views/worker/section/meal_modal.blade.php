<!-- Modal for Adding Meals -->
<div class="modal fade" id="mealModal" tabindex="-1" role="dialog" aria-labelledby="mealModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mealModalLabel">Ajouter un repas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="mealForm">
                    <input type="hidden" id="childIdInput">
                    <div class="form-group">
                        <label for="meal_time">Heure du repas</label>
                        <input type="time" class="form-control" id="meal_time" required>
                    </div>
                    <div class="form-group">
                        <label for="meal_type">Type de repas</label>
                        <select class="form-control" id="meal_type" required onchange="adjustQuantityInput()">
                            <!-- Les options sont injectées par JavaScript -->
                        </select>
                    </div>
                    <div class="form-group" id="quantity_group">
                        <label for="quantity">Quantité</label>
                        <input type="text" class="form-control" id="quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes"></textarea>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="submitMealForm()">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>
