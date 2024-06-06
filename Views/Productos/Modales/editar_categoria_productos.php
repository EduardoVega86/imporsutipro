<style>
    .modal-content {
    border-radius: 15px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.modal-header {
    background-color: #171931;
    color: white;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
}

.modal-header .btn-close {
    color: white;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    border-top: none;
    padding: 10px 20px;
}

.modal-footer .btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.modal-footer .btn-primary {
    background-color: #ffc107;
    border-color: #ffc107;
    color: white;
}
</style>

<div class="modal fade" id="editar_categoriaModal" tabindex="-1" aria-labelledby="editar_categoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editar_categoriaModalLabel"><i class="fas fa-edit"></i> Editar Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" placeholder="Nombre">
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea class="form-control" id="descripcion" rows="3" placeholder="Descripción"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="online">Online:</label>
                        <select class="form-control" id="online">
                            <option>SI</option>
                            <option>NO</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tipo">Tipo:</label>
                        <select class="form-control" id="tipo">
                            <option>PRINCIPAL</option>
                            <option>SECUNDARIO</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="categoriaPrincipal">Categoria Principal:</label>
                        <select class="form-control" id="categoriaPrincipal">
                            <option>-- Selecciona --</option>
                            <!-- Agregar opciones según sea necesario -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="estado">Estado:</label>
                        <select class="form-control" id="estado">
                            <option>Activo</option>
                            <option>Inactivo</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>