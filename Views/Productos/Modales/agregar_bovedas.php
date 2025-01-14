<!-- Modal Agregar Bóveda -->
<div class="modal fade" id="modalAgregarBoveda" tabindex="-1" aria-labelledby="modalAgregarBovedaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalAgregarBovedaLabel">Nuevo Producto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <form id="formAgregarBoveda">
                    <!-- Nombre -->
                    <div class="mb-3">
                        <label for="nombreBoveda" class="form-label">Nombre </label>
                        <select id="nombreBoveda" name="nombreBoveda" class="form-select" required>
                            <!-- Opciones dinámicas -->
                        </select>
                    </div>

                    <!-- Categoría -->
                    <div class="mb-3">
                        <label for="categoriaBoveda" class="form-label">Categoría </label>
                        <select id="categoriaBoveda" name="categoriaBoveda" class="form-select" required>
                            <!-- Opciones dinámicas -->
                        </select>
                    </div>

                    <!-- Proveedor -->
                    <div class="mb-3">
                        <label for="proveedorBoveda" class="form-label">Proveedor</label>
                        <select id="proveedorBoveda" name="proveedorBoveda" class="form-select" required>
                            <!-- Opciones dinámicas -->
                        </select>
                    </div>

                    <!-- Ejemplo Landing -->
                    <div class="mb-3">
                        <label for="ejemploLanding" class="form-label">Ejemplo Landing</label>
                        <input type="text" id="ejemploLanding" name="ejemploLanding" class="form-control">
                    </div>

                    <!-- Duplicar Funnel -->
                    <div class="mb-3">
                        <label for="duplicarFunnel" class="form-label">Duplicar Funnel</label>
                        <input type="text" id="duplicarFunnel" name="duplicarFunnel" class="form-control">
                    </div>

                    <!-- Videos -->
                    <div class="mb-3">
                        <label for="videosBoveda" class="form-label">Videos</label>
                        <input type="text" id="videosBoveda" name="videosBoveda" class="form-control">
                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                            Cerrar
                        </button>
                        <button
                            type="submit"
                            class="btn btn-success">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>