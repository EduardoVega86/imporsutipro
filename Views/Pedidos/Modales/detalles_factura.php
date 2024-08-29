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

    .texto_modal {
        font-size: 20px;
        margin-bottom: 5px;
    }

    .descripcion_producto {
        display: flex;
        flex-direction: row;
    }

    .informacion_producto {
        width: 50%;
        /* Aproximadamente la mitad del contenedor, similar a col-6 */
        margin-bottom: 1rem;
        /* Espaciado en la parte inferior, similar a mb-4 */
    }

    @media (max-width: 768px) {
        .descripcion_producto {
            flex-direction: column-reverse;
        }

        .informacion_producto {
            width: 100%;
        }
    }

    /* tabla de detalle factura */
    .custom-table {
        width: 100%;
        margin: 20px 0;
        border-collapse: collapse;
    }

    .custom-table thead th {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 8px;
    }

    .custom-table tbody td {
        border: 1px solid #dee2e6;
        padding: 8px;
    }

    .custom-total-row {
        font-weight: bold;
    }

    /* fin de tabla detalle factura */
</style>
<div class="modal fade" id="detalles_facturaModal" tabindex="-1" aria-labelledby="detalles_facturaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalles_facturaModalLabel"><i class="fas fa-edit"></i> Detalle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="informacion_producto">
                    <h3 class="mb-3" style="text-decoration:underline;"><strong>Detalle Factura</strong></h3>
                    <p class="texto_modal"><strong>Orden para:</strong> <span id="ordePara_detalleFac"></span></p>
                    <p class="texto_modal"><strong>Dirección:</strong> <span id="direccion_detalleFac"></span></p>
                    <p class="texto_modal"><strong>Teléfono:</strong> <span id="telefono_detalleFac"></span></p>
                    <p class="texto_modal"><strong># Orden:</strong> <span id="numOrden_detalleFac"></span></p>
                    <p class="texto_modal"><strong>Fecha:</strong> <span id="fecha_detalleFac"></span></p>
                    <p class="texto_modal"><strong>Compañia de envío:</strong> <span id="companiaEnvio_detalleFac"></span></p>
                    <p class="texto_modal"><strong>Tipo de envio:</strong> <span id="tipoEnvio_detalleFac"></span></p>
                </div>

                <div>
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Descuento %</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="tabla_body">
                            
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>