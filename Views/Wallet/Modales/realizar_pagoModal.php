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

    .bnt_elegir {
        background-color: #1337EC;
        border-color: #1337EC;
        color: white;
    }

    .bnt_elegir:hover {
        background-color: #102BB4;
        border-color: #102BB4;
        color: white;
    }
</style>

<div class="modal fade" id="realizar_pagoModal" tabindex="-1" aria-labelledby="realizar_pagoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="realizar_pagoModalLabel"><i class="fas fa-edit"></i> Realizar Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="realizar_pago">
                    <input type="hidden" id="id_plataforma" name="id_plataforma">
                    <div class="mb-3">
                        <label for="monto" class="form-label">Monto:</label>
                        <input type="text" class="form-control" id="monto" placeholder="Ingresar monto">
                    </div>

                    <div class="mb-3">
                        <label for="numero_documento" class="form-label">Numero de documento:</label>
                        <input type="text" class="form-control" id="numero_documento" placeholder="Ingresar Numero documento">
                    </div>

                    <div class="mb-3">
                        <label for="forma_pago" class="form-label">Elegir forma de pago:</label>
                        <select class="form-select" id="forma_pago">
                            <option value="0">-- Seleccione una forma de pago --</option>
                            <option value="transferencia_bancaria">Transferencia Bancaria</option>
                            <option value="cheque">Cheque</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="giro">Giro</option>
                            <option value="USDT">USDT</option>
                            <option value="PAYONEER">PAYONEER</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="comprobante" class="form-label">Agregar Comprobante:</label>
                        <input type="file" class="form-control" id="comprobante" name="comprobante">
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" form="realizar_pago">Pagar</button>
            </div>
        </div>
    </div>
</div>

<script>
    /* // Función para reiniciar el formulario
    function resetForm() {
        $('#realizar_pago')[0].reset();

    }

    // Evento para reiniciar el formulario cuando se cierre el modal
    $('#agregar_productoModal').on('hidden.bs.modal', function() {
        resetForm();
        var button = document.getElementById('guardar_producto');
        button.disabled = false; // activar el botón
    }); */

    // Manejar el envío del formulario
    $('#realizar_pago').on('submit', function(event) {
        event.preventDefault();

        /* var button = document.getElementById('guardar_producto');
        button.disabled = true; // Desactivar el botón
        event.preventDefault(); // Evitar el envío normal del formulario */

        let formData = new FormData(); // Crear el FormData directamente del formulario

        // Agregar el archivo de comprobante
        let comprobante = $('#comprobante')[0].files[0];
        if (comprobante) {
            formData.append('imagen', comprobante);
            formData.append('valor', $('#monto').val());
            formData.append('documento', $('#numero_documento').val());
            formData.append('forma_pago', $('#forma_pago').val());
            formData.append('id_plataforma', $('#id_plataforma').val());
        }
        
        $.ajax({
            url: SERVERURL + 'wallet/pagarFactura',
            method: 'POST',
            data: formData,
            processData: false, // No procesar los datos
            contentType: false, // No establecer ningún tipo de contenido
            success: function(response) {
                response = JSON.parse(response);
                if (response.status == 400) {
                    Swal.fire({
                        icon: 'error',
                        title: "Error",
                        text: response.message
                    });
                } else if (response.status == 200) {
                    Swal.fire({
                        icon: 'success',
                        title: "Exito",
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        cargar_saldoWallet();
                        initDataTableFacturas();
                        cargarDashboard_wallet();
                        $('#realizar_pagoModal').modal('hide');
                    });
                }
            },
            error: function(error) {
                console.error('Error al solicitar el pago:', error);
                alert('Hubo un error al solicitar el pago.');
            }
        });
    });
</script>