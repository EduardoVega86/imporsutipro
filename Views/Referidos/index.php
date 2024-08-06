<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Referidos/css/referidos_style.php'; ?>

<?php require_once './Views/Referidos/Modales/solicitar_pago_referido.php'; ?>

<script>
    const referido_generado = <?php echo $data; ?>
</script>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">

        <div class="left_right gap-2">
            <div class="table-responsive left">
                <div class="card text-center">
                    <div class="card-body">
                        <img src="" id="image_tienda" width="100px" class="rounded-circle mb-3" alt="Profile Picture">
                        <h5 class="card-title"><a href="#" id="tienda_url"><span id="tienda_span"></span></a></h5>

                        <div class="col-12 mb-3">
                            <div class="d-flex flex-row gap-2" style="justify-content: center;">
                            <button class="btn btn-primary mb-3" onclick="generar_referido()"><i class="fa-solid fa-arrow-left"></i> Generar link Referido</button>
                            <button type="button" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#solicitar_pago_referidoModal">
                                Solicitar Pago
                            </button>
                            </div>
                            <div id="link_referido" style="display: none;">
                                <input type="text" class="form-control" value="<?php echo SERVERURL . "refers/" . $_SESSION['id_plataforma']; ?>" disabled>
                            </div>
                        </div>
                        <div class="row text-start">
                            <div class="col-12 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-cart-fill fs-1 text-primary"></i>
                                                <div class="ms-3">
                                                    <p class="mb-0"><strong>Cantidad de referidos:</strong></p>
                                                    <h3 class="text-primary"><span id="cantidad_referidos"></span></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-cart-fill fs-1 text-primary"></i>
                                                <div class="ms-3">
                                                    <p class="mb-0"><strong>Ganacia Historico:</strong></p>
                                                    <h3 class="text-primary">$<span id="ganancia_historico_referidos"></span></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-currency-dollar fs-1 text-success"></i>
                                                <div class="ms-3">
                                                    <p class="mb-0"><strong>Ganancias con Referidos:</strong></p>
                                                    <h3 class="text-success">$<span id="ganancias_referidos"></span></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="right gap-2">
                <h3 style="text-align: center; padding-top:5px;">Lista de Referidos</h3>
                <div class="table-responsive">
                    <table id="datatable_referidos" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="centered">ID</th>
                                <th class="centered">Nombre tienda</th>
                                <th class="centered">URL</th>
                                <th class="centered">Telefono</th>
                                <th class="centered">Fecha</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody_referidos"></tbody>
                    </table>
                </div>
                <h3 style="text-align: center; padding-top:5px;">Guias Referidos</h3>
                <div class="table-responsive">
                    <table id="datatable_guias_referidos" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="centered">Numero referido</th>
                                <th class="centered">Guia</th>
                                <th class="centered">Monto</th>
                                <th class="centered">Fecha</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody_guias_referidos"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        if (referido_generado) {
            $("#link_referido").show();
        } else {
            $("#link_referido").hide();
        }
    })
</script>
<script src="<?php echo SERVERURL ?>/Views/Referidos/js/referidos.js"></script>
<?php require_once './Views/templates/footer.php'; ?>