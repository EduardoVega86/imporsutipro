<?php require_once './Views/templates/header.php'; ?>
<style>
    .section-title {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
    }

    .form-section {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-custom {
        width: 100%;
    }
</style>

<div class="custom-container-fluid mt-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="section-title">Generar Guías</h2>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 form-section">
            <form>
                <div class="row g-3 align-items-center mb-3">
                    <div class="col-auto">
                        <label for="cantidad" class="col-form-label">Cant:</label>
                    </div>
                    <div class="col-auto">
                        <input type="number" id="cantidad" class="form-control" value="1" min="1">
                    </div>
                    <div class="col-auto">
                        <label for="codigo" class="col-form-label">Código:</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" id="codigo" class="form-control">
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary">Buscar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 form-section">
            <h5>Datos Destinatario</h5>
            <form>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre y Apellido</label>
                    <input type="text" class="form-control" id="nombre" placeholder="Nombre y Apellido">
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" placeholder="Teléfono">
                </div>
                <div class="mb-3">
                    <label for="ciudad" class="form-label">Ciudad</label>
                    <select id="ciudad" class="form-select">
                        <option selected>Selecciona una opción</option>
                        <!-- Agregar opciones aquí -->
                    </select>
                </div>
                <div class="mb-3">
                    <label for="provincia" class="form-label">Provincia</label>
                    <select id="provincia" class="form-select">
                        <option selected>Selecciona una opción</option>
                        <!-- Agregar opciones aquí -->
                    </select>
                </div>
                <div class="mb-3">
                    <label for="calle_principal" class="form-label">Calle Principal</label>
                    <input type="text" class="form-control" id="calle_principal" placeholder="Calle Principal">
                </div>
                <div class="mb-3">
                    <label for="calle_secundaria" class="form-label">Calle Secundaria</label>
                    <input type="text" class="form-control" id="calle_secundaria" placeholder="Calle Secundaria">
                </div>
                <div class="mb-3">
                    <label for="numero_casa" class="form-label">Número de Casa</label>
                    <input type="text" class="form-control" id="numero_casa" placeholder="Número de Casa">
                </div>
                <div class="mb-3">
                    <label for="referencia" class="form-label">Referencia</label>
                    <input type="text" class="form-control" id="referencia" placeholder="Referencia">
                </div>
                <div class="mb-3">
                    <label for="observaciones" class="form-label">Observaciones para la entrega</label>
                    <input type="text" class="form-control" id="observaciones" placeholder="Referencias Adicionales (Opcional)">
                </div>
            </form>
        </div>

        <div class="col-md-6 form-section">
            <h5>Generar Guías</h5>
            <div class="d-flex justify-content-around mb-4">
                <!-- Agregar imágenes o iconos según sea necesario -->
                <div class="text-center">
                    <img src="path/to/servientrega_logo.png" alt="Servientrega">
                </div>
                <div class="text-center">
                    <img src="path/to/laborcourier_logo.png" alt="Laborcourier">
                </div>
                <div class="text-center">
                    <img src="path/to/speed_logo.png" alt="Speed">
                </div>
                <div class="text-center">
                    <img src="path/to/gintracom_logo.png" alt="Gintracom">
                </div>
            </div>
            <form>
                <div class="mb-3">
                    <label for="recaudo" class="form-label">Recaudo</label>
                    <select id="recaudo" class="form-select">
                        <option value="1">Con Recaudo</option>
                        <option value="0">Sin Recaudo</option>
                    </select>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="asegurar_mercaderia">
                    <label class="form-check-label" for="asegurar_mercaderia">
                        Deseo asegurar la mercadería
                    </label>
                </div>
                <div class="mb-3">
                    <label for="valor_asegurar" class="form-label">Valor a asegurar</label>
                    <input type="text" class="form-control" id="valor_asegurar" placeholder="Valor a asegurar">
                </div>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-success btn-custom">Guardar Pedido</button>
                    <button type="button" class="btn btn-danger btn-custom">Generar Guía</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once './Views/templates/footer.php'; ?>