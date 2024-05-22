<!-- Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">Ajouter/Modifier Photo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="photoForm" enctype="multipart/form-data">
                <input type="hidden" id="childId" name="child_id">
                <input type="hidden" id="photoId" name="photo_id">

                <div class="form-group">
                    <label for="photo_description">Description</label>
                    <input type="text" class="form-control" id="photo_description" name="description">
                </div>
                <div class="form-group">
                    <label for="taken_at">Date de la prise</label>
                    <input type="time" class="form-control" id="taken_at" name="taken_at" required>
                </div>

                <div class="form-group">
                    <label for="photo_path">Photo</label>
                    <input type="file" class="form-control" id="photo_path" name="photo" accept="image/*" onchange="previewImage();">
                    <div id="image_preview_container" style="margin-top: 20px;">
                        <img id="image_preview" src="#" alt="your image" style="max-width: 100%; display: none;" />
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-primary" onclick="submitPhotoForm()">Enregistrer</button>
                </div>
            </form>

            </div>
        </div>
    </div>
</div>
