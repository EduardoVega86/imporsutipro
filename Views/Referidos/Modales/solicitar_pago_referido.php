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

<div class="modal fade" id="solicitar_pago_referidoModal" tabindex="-1" aria-labelledby="solicitar_pago_referidoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="solicitar_pago_referidoModalLabel"><i class="fas fa-edit"></i> Solicitar Pago Referido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="solicitar_pago_referido">
                    <input type="hidden" id="otroId" name="otroId">
                    <div class="mb-3">
                        <label for="monto" class="form-label">Monto:</label>
                        <input type="text" class="form-control" id="monto" placeholder="Ingresar monto">
                    </div>

                    <div class="d-flex flex-row gap-3">
                        <button type="button" class="btn bnt_elegir" onclick="elegirCuenta()">Elegir cuenta bancaria</button>
                        <button type="button" class="btn bnt_elegir" onclick="formaPago()">Elegir otra forma de pago</button>
                    </div>
                    <div class="mb-3" id="elegir_cuenta" style="display: none;">
                        <label for="cuenta" class="form-label">Elegir cuenta:</label>
                        <select class="form-select" id="cuenta">
                            <option value="">-- Seleccione una cuenta --</option>
                        </select>
                    </div>

                    <div class="mb-3" id="forma_pago" style="display: none;">
                        <label for="formadePago" class="form-label">Elegir forma de pago:</label>
                        <select class="form-select" id="formadePago">
                            <option value="">-- Seleccione una cuenta --</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" form="solicitar_pago_referido">Solicitar</button>
            </div>
        </div>
    </div>
</div>
<script>
    // Manejar el envío del formulario
    $('#solicitar_pago_referido').on('submit', function(event) {
        event.preventDefault(); // Evitar el envío normal del formulario

        let formData = new FormData();
        formData.append("monto", $('#monto').val());
        if ($('#cuenta').val() == ""){
            formData.append("cuenta", $('#formadePago').val());
        }else{
            formData.append("cuenta", $('#cuenta').val());
        }
        formData.append("otro", $('#otroId').val());

        $.ajax({
            url: SERVERURL + 'referidos/solicitar_pago',
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
                        $('#solicitar_pago_referidoModal').modal('hide');
                    });
                }
            },
            error: function(error) {
                console.error('Error al solicitar el pago:', error);
                alert('Hubo un error al solicitar el pago.');
            }
        });
    });

    function elegirCuenta() {
        $("#elegir_cuenta").show();
        $("#forma_pago").hide();
        $("#otroId").val(0);
    }

    function formaPago() {
        $("#elegir_cuenta").hide();
        $("#forma_pago").show();
        $("#otroId").val(1);
    }
</script>