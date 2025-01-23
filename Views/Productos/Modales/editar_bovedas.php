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
                    <!-- Nombre -->
                    <div class="mb-3">
                        <label for="editNombreBoveda" class="form-label">Nombre </label>
                        <select id="editNombreBoveda" name="editNombreBoveda" class="form-select" required>
                            <!-- Opciones dinámicas -->
                        </select>
                    </div>

                    <!-- Categoría -->
                    <div class="mb-3">
                        <label for="categoriaBoveda" class="form-label">Categoría </label>
                        <select id="editCategoriaBoveda" name="editCategoriaBoveda" class="form-select" required>
                            <!-- Opciones dinámicas -->
                        </select>
                    </div>

                    <!-- Proveedor -->
                    <div class="mb-3">
                        <label for="editProveedorBoveda" class="form-label">Proveedor</label>
                        <select id="editProveedorBoveda" name="editProveedorBoveda" class="form-select" required>
                            <!-- Opciones dinámicas -->
                        </select>
                    </div>
                    <!--Imagen -->
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen</label>
                        <input type="file" class="form-control" id="Editarimagen" name="imagen" accept="image/*">
                        <img id="preview-imagen" src="#" alt="Vista previa de la imagen" style="display: none; margin-top: 10px; max-width: 100%;">
                    </div>
                    <div class="mb-3">
                        <label for="editPlantillasVentas" class="form-label">Plantillas de Ventas</label>
                        <input type="url" class="form-control" id="editPlantillasVentas">
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