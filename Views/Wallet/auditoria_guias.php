<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Wallet/css/auditoria_guias_style.php'; ?>


<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Auditoria</h2>
        <div class="d-flex flex-column justify-content-between">

<div class="filter-container">
               <form id="uploadForm" enctype="multipart/form-data">

            <div class="form-group w-100 hidden-field" id="bodega-field">
                <label for="bodega">Transportadora carga:</label>
                <select name="transporte_importacion" id="transporte_importacion" class="form-control">
                            <option value="0"> Seleccione Transportadora</option>
                            <option value="1">Laar</option>
                            <option value="4">Speed</option>
                            <option value="2">Servientrega</option>
                            <option value="3">Gintracom</option>
                        </select>
            </div>
            <div class="form-group">
                <label for="fileInput">Seleccionar archivo:</label>
                <input type="file" class="form-control-file" id="fileInput" name="file" required>
            </div>
            <button id="enviar_importacion" type="submit" class="btn btn-success">Cargar</button>
        </form>
            </div>
            <div class="segunda_seccionFiltro">

                <div style="width: 100%;">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Transportadora</label>
                    <div>
                        <select name="transporte" id="transporte" class="form-control">
                            <option value="0"> Seleccione Transportadora</option>
                            <option value="1">Laar</option>
                            <option value="4">Speed</option>
                            <option value="2">Servientrega</option>
                            <option value="3">Gintracom</option>
                        </select>
                    </div>
                </div>
                
            </div>
        </div>
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
                        <th class="centered">COD</th>
                        <th class="centered">Monto Factura</th>
                        <th class="centered">Valor flete</th>
                        <th class="centered">Costo flete</th>
                        <th class="centered">Valor cod</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tableBody_auditoria"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Wallet/js/solicitudes.js"></script>
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
                    alert('Error al subir el archivo');
                }
            });
        });
    });
    
    window.addEventListener("load", async () => {
        await initDataTableAuditoria(0, 0);
    });
</script>
<?php require_once './Views/templates/footer.php'; ?>