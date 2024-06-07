<style>
    .form-group {
        margin-bottom: 15px;
    }

    /* .modal-header {
        background-color: #343a40;
        color: white;
    } */

    .hidden-tab {
        display: none !important;
    }
</style>

<div class="modal fade" id="editar_productoModal" tabindex="-1" aria-labelledby="editar_productoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editar_productoModalLabel"><i class="fas fa-edit"></i> Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="editarTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="editar-datos-basicos-tab" data-bs-toggle="tab" data-bs-target="#editar-datos-basicos" type="button" role="tab" aria-controls="editar-datos-basicos" aria-selected="true"><strong>Datos Básicos</strong></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="editar-precios-stock-tab" data-bs-toggle="tab" data-bs-target="#editar-precios-stock" type="button" role="tab" aria-controls="editar-precios-stock" aria-selected="false"><strong>Precios y Stock</strong></button>
                    </li>
                </ul>
                <div class="tab-content" id="editarTabContent">
                    <div class="tab-pane fade show active" id="editar-datos-basicos" role="tabpanel" aria-labelledby="editar-datos-basicos-tab">
                        <form id="editar_producto_form">
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-row gap-3">
                                    <div class="form-group w-100">
                                        <label for="editar_codigo">Código:</label>
                                        <input type="text" class="form-control" id="editar_codigo">
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="editar_nombre">Nombre:</label>
                                        <input type="text" class="form-control" id="editar_nombre">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="editar_descripcion">Descripción:</label>
                                    <textarea class="form-control" id="editar_descripcion"></textarea>
                                </div>
                                <div class="d-flex flex-row gap-3">
                                    <div class="d-flex flex-column w-100">
                                        <div class="form-group">
                                            <label for="editar_categoria">Categoría:</label>
                                            <select class="form-select" id="editar_categoria">
                                                <option selected>-- Selecciona Categoría --</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Formato:</label>
                                            <div class="d-flex">
                                                <img src="formato1.png" alt="Formato 1" class="me-2">
                                                <img src="formato2.png" alt="Formato 2">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column w-100">
                                        <div class="form-group">
                                            <label for="editar_formato-pagina">Formato Página Productos:</label>
                                            <select class="form-select" id="editar_formato-pagina">
                                                <option selected>-- Selecciona --</option>
                                                <option value="1">Formato 1</option>
                                                <option value="2">Formato 2</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="editar-precios-stock" role="tabpanel" aria-labelledby="editar-precios-stock-tab">
                        <form id="editar_precios_form">
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-row gap-3">
                                    <div class="form-group w-100">
                                        <label for="editar_ultimo_costo">Ultimo Costo:</label>
                                        <input type="text" class="form-control" id="editar_ultimo_costo">
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="editar_utilidad">Utilidad %:</label>
                                        <input type="text" class="form-control" id="editar_utilidad">
                                    </div>
                                </div>
                                <div class="d-flex flex-row gap-3">
                                    <div class="form-group w-100">
                                        <label for="editar_precio_proveedor">Precio Proveedor:</label>
                                        <input type="text" class="form-control" id="editar_precio_proveedor">
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="editar_precio_venta">Precio de Venta (Sugerido):</label>
                                        <input type="text" class="form-control" id="editar_precio_venta">
                                    </div>
                                </div>
                                <div class="d-flex flex-row gap-3">
                                    <div class="form-group w-100">
                                        <label for="editar_precio_referencial">Precio Referencial</label>
                                        <input type="text" class="form-control mt-2" id="editar_precio_referencial">
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="editar_maneja_inventario">Maneja Inventario:</label>
                                        <select class="form-select" id="editar_maneja_inventario">
                                            <option selected>-- Selecciona --</option>
                                            <option value="1">Sí</option>
                                            <option value="2">No</option>
                                        </select>
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="editar_stock_inicial">Stock Inicial:</label>
                                        <input type="text" class="form-control" id="editar_stock_inicial">
                                    </div>
                                </div>
                                <div class="form-group w-100 hidden-field" id="bodega-field">
                                    <label for="editar_bodega">Bodega:</label>
                                    <select class="form-select" id="editar_bodega">
                                        <option selected>-- Selecciona Bodega --</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>