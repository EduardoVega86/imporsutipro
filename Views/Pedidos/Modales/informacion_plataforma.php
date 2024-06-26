<style>
    .modal-content {
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: <?php echo COLOR_FONDO; ?>;
        color: <?php echo COLOR_LETRAS; ?>;
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
<div class="modal fade" id="infoTiendaModal" tabindex="-1" aria-labelledby="infoTiendaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoTiendaModalLabel"><i class="fas fa-edit"></i> Información de la Tienda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            Información de la tienda
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nombreTienda">Nombre</label>
                                <input type="text" class="form-control" id="nombreTienda" name="nombreTienda" readonly>
                            </div>
                            <div class="form-group">
                                <label for="telefonoTienda">Teléfono</label>
                                <input type="text" class="form-control" id="telefonoTienda" name="telefonoTienda" readonly>
                            </div>
                            <div class="form-group">
                                <label for="correoTienda">Correo</label>
                                <input type="email" class="form-control" id="correoTienda" name="correoTienda" readonly>
                            </div>
                            <div class="form-group">
                                <label for="enlaceTienda">Enlace</label>
                                <input type="url" class="form-control" id="enlaceTienda" name="enlaceTienda" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>