<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/combos_style.php'; ?>
<?php require_once './Views/Productos/Modales/agregar_combo.php'; ?>
<?php require_once './Views/Productos/Modales/editar_combo.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Gestion de Productos Privados</h2>
        <!-- <div class="filtros_producos justify-content-between align-items-center mb-3"></div>
        </div> -->
        <div class="left_right gap-2">
            <div class="table-responsive left">
                <div class="justify-content-between align-items-center mb-3">
                    <div class="d-flex">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_comboModal"><i class="fas fa-plus"></i> Agregar</button>
                    </div>
                </div>
                <!-- <table class="table table-bordered table-striped table-hover"> -->
                <table id="datatable_combos" class="table table-striped" style="min-width: 100%;">
                    <thead>
                        <tr>
                            <th class="centered">ID</th>
                            <th class="centered"></th>
                            <th class="centered">Nombre combo</th>
                            <th class="centered">Nombre Producto</th>
                            <th class="centered">Visualizar combo</th>
                            <th class="centered">Accion</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody_combos"></tbody>
                </table>
            </div>
            <div class="right gap-2 hidden" id="comboSection">
                <input type="hidden" id="id_combo_seccion" name="id_combo_seccion">
                <div class="card" style="height: auto; padding: 10px;">
                    <div class="table-responsive">
                        <table id="datatable_asignacion_producto" class="table table-striped" style="min-width: 100%;">
                            <thead>
                                <tr>
                                    <th class="centered">ID prodcuto</th>
                                    <th class="centered"></th>
                                    <th class="centered">Nombre Producto</th>
                                    <th class="centered">Precio</th>
                                    <th class="centered">Cantidad</th>
                                    <th class="centered">Mover</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody_asignacion_producto"></tbody>
                        </table>
                    </div>
                </div>
                <div class="card" style="height: auto; padding: 10px;">
                    <div class="table-responsive">
                        <table id="datatable_detalle_combo" class="table table-striped" style="min-width: 100%;">
                            <thead>
                                <tr>
                                    <th class="centered">ID Producto</th>
                                    <th class="centered"></th>
                                    <th class="centered">Nombre Producto</th>
                                    <th class="centered">Cantidad</th>
                                    <th class="centered">Mover</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody_detalle_combo"></tbody>
                        </table>
                    </div>
                    <!-- seccion preview combo -->
                    <div class="custom-card">
                        <div class="custom-card-header">
                            PAGA AL RECIBIR EN CASA! POCAS UNIDADES
                        </div>
                        <div class="custom-card-body">
                            <div class="custom-product">
                                <img src="" alt="Producto" id="imagen_combo_preview" class="custom-product-image">
                                <div class="custom-product-info">
                                    <span id="nombre_combo_preview"></span>
                                    <span class="custom-discount" id="ahorro_preview" style="display: none;">Ahorra 20%</span>
                                </div>
                                <div class="custom-product-price">
                                    <span class="old-price" id="precio_normal_preview">$0</span>
                                    <span class="new-price" id="precio_especial_preview">$0</span>
                                </div>
                            </div>
                            <div class="custom-card-footer">
                                <div class="custom-summary">
                                    <div>Subtotal</div>
                                    <div><span id="subtotal_preview"></span></div>
                                </div>
                                <div class="custom-summary">
                                    <div>Envío</div>
                                    <div class="free-shipping">Gratis</div>
                                </div>
                                <div class="custom-total">
                                    <div>Total</div>
                                    <div class="total-price"><span id="total_preview"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fin seccion preview combo -->
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo SERVERURL ?>/Views/Productos/js/combos.js"></script>
<?php require_once './Views/templates/footer.php'; ?>