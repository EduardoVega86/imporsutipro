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

<div class="modal fade" id="editar_walletModal" tabindex="-1" aria-labelledby="editar_walletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editar_walletModalLabel"><i class="fas fa-edit"></i> Editar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editar_wallet">
                    <div class="mb-3">
                        <label for="total_ventas" class="form-label">total ventas:</label>
                        <input type="text" class="form-control" id="total_ventas" placeholder="Ingresar total ventas">
                    </div>
                    <div class="mb-3">
                        <label for="monto_recibir" class="form-label">monto a recibir:</label>
                        <input type="text" class="form-control" id="monto_recibir" placeholder="Ingresar monto a recibir">
                    </div>
                    <div class="mb-3">
                        <label for="precio_envio" class="form-label">precio de envio:</label>
                        <input type="text" class="form-control" id="precio_envio" placeholder="Ingresar precio de envio">
                    </div>
                    <div class="mb-3">
                        <label for="fulfilment" class="form-label">Fulfilment:</label>
                        <input type="text" class="form-control" id="fulfilment" placeholder="Ingresar fulfilment">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" form="editar_wallet">Editar</button>
            </div>
        </div>
    </div>
</div>
<script>
    // Manejar el envío del formulario
    $('#solicitar_pago').on('submit', function(event) {
        event.preventDefault(); // Evitar el envío normal del formulario

        let formData = new FormData();
        formData.append("valor", $('#monto').val());
        formData.append("id_cuenta", $('#cuenta').val());

        $.ajax({
            url: SERVERURL + 'wallet/solicitarPago',
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
                        $('#solicitar_pagoModal').modal('hide');
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