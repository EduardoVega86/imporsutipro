<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Wallet/css/datos_bancarios_style.php'; ?>


<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Datos Bancarios</h2>
        <div class="left_right gap-2">
            <div class="left">
                <form>
                    <div class="mb-3">
                        <label for="banco" class="form-label">Banco</label>
                        <select class="form-select" id="banco">
                            <option>-- Seleccione un banco --</option>
                            <!-- Opciones de bancos aquí -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tipo-cuenta" class="form-label">Tipo de cuenta</label>
                        <select class="form-select" id="tipo-cuenta">
                            <option>-- Seleccione un tipo de cuenta --</option>
                            <!-- Opciones de tipos de cuenta aquí -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="numero-cuenta" class="form-label">Número de cuenta</label>
                        <input type="text" class="form-control" id="numero-cuenta" placeholder="Numero de cuenta">
                    </div>
                    <div class="mb-3">
                        <label for="nombre-titular" class="form-label">Nombre del Titular</label>
                        <input type="text" class="form-control" id="nombre-titular" placeholder="Nombre del titular">
                    </div>
                    <div class="mb-3">
                        <label for="cedula-titular" class="form-label">Cédula del Titular</label>
                        <input type="text" class="form-control" id="cedula-titular" placeholder="Cédula del titular">
                    </div>
                    <div class="mb-3">
                        <label for="correo-titular" class="form-label">Correo del Titular</label>
                        <input type="email" class="form-control" id="correo-titular" placeholder="Correo del titular">
                    </div>
                    <div class="mb-3">
                        <label for="telefono-titular" class="form-label">Teléfono del Titular</label>
                        <input type="text" class="form-control" id="telefono-titular" placeholder="Teléfono del titular">
                    </div>
                    <button type="submit" class="btn btn-success">Enviar datos</button>
                </form>
            </div>
            <div class="right">
            </div>
        </div>
    </div>
</div>
<!-- <script src="<?php echo SERVERURL ?>/Views/Wallet/js/detalle.js"></script> -->
<?php require_once './Views/templates/footer.php'; ?>