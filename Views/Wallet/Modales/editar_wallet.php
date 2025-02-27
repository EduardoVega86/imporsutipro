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
                    <input type="hidden" id="id_cabeceraEditarWallet" name="id_cabeceraEditarWallet">
                    <div class="mb-3">
                        <label for="total_ventasEditar_Wallet" class="form-label">total ventas:</label>
                        <input type="text" class="form-control" id="total_ventasEditar_Wallet" placeholder="Ingresar total ventas">
                    </div>
                    <div class="mb-3">
                        <label for="costoEditar_Wallet" class="form-label">costo:</label>
                        <input type="text" class="form-control" id="costoEditar_Wallet" placeholder="Ingresar monto a recibir">
                    </div>
                    <div class="mb-3">
                        <label for="precio_envioEditar_Wallet" class="form-label">precio de envio:</label>
                        <input type="text" class="form-control" id="precio_envioEditar_Wallet" placeholder="Ingresar precio de envio">
                    </div>
                    <div class="mb-3">
                        <label for="fulfilmentEditar_Wallet" class="form-label">Fulfilment:</label>
                        <input type="text" class="form-control" id="fulfilmentEditar_Wallet" placeholder="Ingresar fulfilment">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" form="editar_wallet" onclick="editarWallet()">Editar</button>
            </div>
        </div>
    </div>
</div>
<script>
    function editarWallet() {
        var id_cabeceraEditarWallet = $('#id_cabeceraEditarWallet').val();
        var total_ventasEditar_Wallet = $('#total_ventasEditar_Wallet').val();
        var costoEditar_Wallet = $('#costoEditar_Wallet').val();
        var precio_envioEditar_Wallet = $('#precio_envioEditar_Wallet').val();
        var fulfilmentEditar_Wallet = $('#fulfilmentEditar_Wallet').val();

        let formData = new FormData();
        formData.append("total_venta", total_ventasEditar_Wallet);
        formData.append("precio_envio", precio_envioEditar_Wallet);
        formData.append("full", fulfilmentEditar_Wallet);
        formData.append("costo", costoEditar_Wallet);

        $.ajax({
            url: SERVERURL + "wallet/editar/" + id_cabeceraEditarWallet,
            type: "POST",
            data: formData,
            processData: false, // No procesar los datos
            contentType: false, // No establecer ningún tipo de contenido
            success: function(response) {
                response = JSON.parse(response);
                if (response.status == 500) {
                    toastr.error(
                        "NO SE EDITO CORRECTAMENTE",
                        "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center"
                        }
                    );
                } else if (response.status == 200) {
                    toastr.success("SE EDITO CORRECTAMENTE", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center",
                    });

                    initDataTableFacturas();
                    $('#editar_walletModal').modal('hide');
                } else if (response.status == 201) {
                    toastr.warning("NO SE REALIZO NINGUN CAMBIO", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center",
                    });
                } else if (response.status == 501) {
                    toastr.error(
                        response.message,
                        "NOTIFICACIÓN", {
                            positionClass: "toast-bottom-center"
                        }
                    );
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
        });
    }
</script>