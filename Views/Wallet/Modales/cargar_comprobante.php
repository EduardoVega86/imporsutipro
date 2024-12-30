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

    .modal-body img {
        max-width: 60%;
        /* Imagen más pequeña inicialmente */
        height: auto;
        cursor: grab;
        transition: transform 0.3s ease, max-width 0.3s ease;
    }

    .modal-body img.zoomed {
        max-width: 120%;
        /* Amplía la imagen al hacer zoom */
        transform: translate(0, 0);
        /* Resetea el desplazamiento al aplicar zoom */
        cursor: move;
        /* Cambia el cursor para mover */
    }

    .modal-body {
        overflow: hidden;
        /* Esconde partes de la imagen que salen del contenedor */
        position: relative;
        text-align: center !important;
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

<div class="modal fade" id="cargar_comprobanteModal" tabindex="-1" aria-labelledby="cargar_comprobanteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cargar_comprobanteModalLabel"><i class='fas fa-receipt'></i> Comprobante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Imagen se cargará aquí dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>