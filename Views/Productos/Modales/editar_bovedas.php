<div class="modal fade" id="modalEditarBoveda" tabindex="-1" aria-labelledby="modalEditarBovedaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarBovedaLabel">Editar Bóveda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarBoveda">
                    <input type="hidden" id="editar_idBoveda" name="editar_idBoveda">
                    <div class="mb-3">
                        <label for="editNombreBoveda" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="editNombreBoveda" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCategoriaBoveda" class="form-label">Categoría</label>
                        <input type="text" class="form-control" id="editCategoriaBoveda" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProveedorBoveda" class="form-label">Proveedor</label>
                        <input type="text" class="form-control" id="editProveedorBoveda" required>
                    </div>
                    <!--Imagen -->
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen</label>
                        <input type="file" class="form-control" id="Editarimagen" name="imagen" accept="image/*">
                        <img id="preview-imagen" src="#" alt="Vista previa de la imagen" style="display: none; margin-top: 10px; max-width: 100%;">
                    </div>
                    <div class="mb-3">
                        <label for="editEjemploLanding" class="form-label">Ejemplo Landing</label>
                        <input type="url" class="form-control" id="editEjemploLanding">
                    </div>
                    <div class="mb-3">
                        <label for="editDuplicarFunnel" class="form-label">Duplicar Funnel</label>
                        <input type="url" class="form-control" id="editDuplicarFunnel">
                    </div>
                    <div class="mb-3">
                        <label for="editVideosBoveda" class="form-label">Videos</label>
                        <input type="url" class="form-control" id="editVideosBoveda">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>