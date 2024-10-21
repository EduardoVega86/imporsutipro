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

    .boton_eliminar_etiqueta {
        background-color: transparent;
        border: hidden;
        color: #afaea9;
    }

    .boton_eliminar_etiqueta:hover {
        color: black;
    }

    @media (max-width: 768px) {
        .descripcion_producto {
            flex-direction: column-reverse;
        }

        .informacion_producto {
            width: 100%;
        }
    }
</style>
<div class="modal fade" id="agregar_numero_clienteModal" tabindex="-1" aria-labelledby="agregar_numero_clienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregar_numero_clienteModalLabel"><i class="fas fa-edit"></i> Asignar nuevo telefono</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Agregar etiqueta -->
                <div class="card mb-4">
                    <div class="card-header">
                        Agregar etiqueta
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="numero_telefono_creacion" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="numero_telefono_creacion" placeholder="Ingrese el telefono" onclick="validar_telefono_chat(this.value)">
                            <div id="telefono-error" style="color: red; display: none;">Este telefono ya existe.</div>

                            <div id="seccion_informacion_numero" style="display: none;">
                                <label for="nombre_creacion" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre_creacion" placeholder="Ingrese el nombre">

                                <label for="apellido_creacion" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido_creacion" placeholder="Ingrese el apellido">
                            </div>

                            <button type="button" class="btn btn-primary" onclick="agregar_numero_cliente()">Agregar</button>
                        </div>
                    </div>
                </div>

                <!-- Envio de tempale -->
                <div class="card">
                    <div class="card-header">
                        Lista de plantillas whatsapp
                    </div>
                    <div class="card-body">

                        <div id="lista_tempaltes">

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

<script>
    function validar_telefono_chat(telefono){
        
    }
</script>