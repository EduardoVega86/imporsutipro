<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/marketplacepro_style.php'; ?>

<div class="custom-container-fluid mt-4">
    <div style="padding-bottom: 20px; padding-top: 20px;">
        <div class="caja p-4 shadow-sm bg-white">
            <!-- Fila : Proveedores -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="slider-proveedores-container">
                        <h6>Proveedores</h6>
                        <input type="text" class="form-control mb-3" placeholder="Nombre de proveedor a encontrar.." id="buscar_nombre" />
                        <div id="sliderProveedores" class="proveedores-grid"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="padding: 10px;">
        <div id="loading-indicator" style="display: none;">Cargando...</div>
    </div>
</div>

<style>
    .proveedores-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        padding: 20px;
    }

    .proveedor-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 15px;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .proveedor-card:hover {
        transform: translateY(-5px);
    }

    .proveedor-logo-container {
        width: 100px;
        height: 100px;
        margin: 0 auto 10px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f0f0;
    }

    .proveedor-logo {
        width: 100%;
        height: auto;
    }

    .proveedor-nombre {
        font-size: 16px;
        font-weight: bold;
    }

    .proveedor-productos {
        font-size: 14px;
        color: #555;
    }

    .proveedor-categorias {
        font-size: 12px;
        color: #888;
    }
</style>

<script src="<?php echo SERVERURL ?>/Views/Productos/js/proveedores_pro.js"></script>
<?php require_once './Views/templates/footer.php'; ?>