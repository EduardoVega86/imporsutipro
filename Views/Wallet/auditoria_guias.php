<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Wallet/css/auditoria_guias_style.php'; ?>

<div class="custom-container-fluid">
    
    <div class="container mt-5" style="max-width: 100%;">
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <div class="filter-container">
                <button class="filter-btn active" data-filter="0">Pendientes</button>
                <button class="filter-btn" data-filter="1">Validados</button>
            </div>
            <table id="datatable_auditoria" class="table table-striped">

                <thead>
                    <tr>
                        <th class="centered">Factura</th>
                        <th class="centered">Numero Guia</th>
                        <th class="centered">Estado Guia</th>
                        <th class="centered">Dropshippin</th>
                        <th class="centered">Transportadora</th>
                        <th class="centered">COD</th>
                        <th class="centered">Monto Factura</th>
                        <th class="centered">Valor flete</th>
                        <th class="centered">Solo flete</th>
                        <th class="centered">Costo flete</th>
                        <th class="centered">Valor cod</th>
                        <th class="centered">Utilidad</th>
                        <th class="centered">Guia</th>
                        <th class="centered">Wallet Monto Recibir</th>
                        <th class="centered">Wallet Saldo</th>
                        <th class="centered">Valor Recaudo</th>
                        <th class="centered">Cod Transportadora</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tableBody_auditoria"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Wallet/js/auditoria_guias.js"></script>
<script>
    $(document).ready(function() {
        $('#uploadForm').on('submit', function(e) {

            var button = document.getElementById("enviar_importacion");
            button.disabled = true; // Desactivar el botón

            // Activar el botón después de 5 segundos (5000 milisegundos)
            setTimeout(function() {
                button.disabled = false;
            }, 5000);

            e.preventDefault(); // Prevenir el envío normal del formulario

            var formData = new FormData();
            formData.append('archivo', $('#fileInput')[0].files[0]); // Añadir archivo al FormData
            formData.append('id_transportadora', $('#transporte_importacion').val()); // Añadir ID de bodega al FormData

            // Mostrar Swal antes de la solicitud AJAX
            Swal.fire({
                title: 'Importando...',
                text: 'Por favor espera mientras se realiza la importación.',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '<?php echo SERVERURL; ?>Wallet/importarExcel', // Ruta del controlador que manejará el archivo
                type: 'POST',
                data: formData,
                contentType: false, // Necesario para que jQuery no añada un tipo de contenido
                processData: false, // Necesario para que jQuery no convierta los datos a una cadena
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.status == 500) {
                        Swal.fire({
                            icon: 'error',
                            title: response.title,
                            text: response.message
                        });
                    } else if (response.status == 200) {
                        Swal.fire({
                            icon: 'success',
                            title: response.title,
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al subir el archivo'
                    });
                }
            });

        });
    });

    window.addEventListener("load", async () => {
        await initDataTableAuditoria(0, 0);
    });
</script>
<?php require_once './Views/templates/footer.php'; ?>