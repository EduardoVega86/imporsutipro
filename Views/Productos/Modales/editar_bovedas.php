<!-- Modal Editar Bóveda -->
<div class="modal fade" id="modalEditarBoveda" tabindex="-1" aria-labelledby="modalEditarBovedaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditarBoveda">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarBovedaLabel">Editar Bóveda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <!-- Campo oculto para almacenar el ID de la bóveda -->
                    <input type="hidden" id="editIdBoveda" name="editIdBoveda">

                    <div class="mb-3">
                        <label for="editNombreBoveda" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="editNombreBoveda" name="editNombreBoveda" required>
                    </div>

                    <div class="mb-3">
                        <label for="editCategoriaBoveda" class="form-label">Categoría</label>
                        <select class="form-select" id="editCategoriaBoveda" name="editCategoriaBoveda" required>
                            <!-- Opciones dinámicas o estáticas, según tu necesidad -->
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editProveedorBoveda" class="form-label">Proveedor</label>
                        <select class="form-select" id="editProveedorBoveda" name="editProveedorBoveda" required>
                            <!-- Opciones dinámicas o estáticas, según tu necesidad -->
                        </select>
                    </div>

                    <!-- Aquí podrías agregar más campos, si fuera necesario -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>