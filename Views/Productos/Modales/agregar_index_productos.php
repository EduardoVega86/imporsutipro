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

    .hidden-field {
        display: none;
    }
</style>

<div class="modal fade" id="agregar_productoModal" tabindex="-1" aria-labelledby="agregar_productoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregar_productoModalLabel"><i class="fas fa-edit"></i> Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="datos-basicos-tab" data-bs-toggle="tab" data-bs-target="#datos-basicos" type="button" role="tab" aria-controls="datos-basicos" aria-selected="true"><strong>Datos Básicos</strong></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="precios-stock-tab" data-bs-toggle="tab" data-bs-target="#precios-stock" type="button" role="tab" aria-controls="precios-stock" aria-selected="false"><strong>Precios y Stock</strong></button>
                        </li>
                        <li class="nav-item hidden-tab" role="presentation" id="inventario-variable-tab">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#inventario-variable" type="button" role="tab" aria-controls="inventario-variable" aria-selected="false"><strong>Inventario Variable</strong></button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="datos-basicos" role="tabpanel" aria-labelledby="datos-basicos-tab">
                            <form id="agregar_producto_form">
                                <div class="d-flex flex-column">
                                    <div class="d-flex flex-row gap-3">
                                        <div class="form-group w-100">
                                            <label for="codigo">Código:</label>
                                            <input type="text" class="form-control" id="codigo" value="10088">
                                        </div>
                                        <div class="form-group w-100">
                                            <label for="nombre">Nombre:</label>
                                            <input type="text" class="form-control" id="nombre">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="descripcion">Descripción:</label>
                                        <textarea class="form-control" id="descripcion"></textarea>
                                    </div>
                                    <div class="d-flex flex-row gap-3">
                                        <div class="d-flex flex-column w-100">
                                            <div class="form-group">
                                                <label for="categoria">Categoría:</label>
                                                <select class="form-select" id="categoria">
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
                                                <label for="formato-pagina">Formato Página Productos:</label>
                                                <select class="form-select" id="formato-pagina">
                                                    <option selected>-- Selecciona --</option>
                                                    <option value="1">Formato 1</option>
                                                    <option value="2">Formato 2</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="tab-pane fade" id="precios-stock" role="tabpanel" aria-labelledby="precios-stock-tab">
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-row gap-3">
                                    <div class="form-group w-100">
                                        <label for="costo">Costo:</label>
                                        <input type="text" class="form-control" id="costo">
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="utilidad">Utilidad %:</label>
                                        <input type="text" class="form-control" id="utilidad">
                                    </div>
                                </div>
                                <div class="d-flex flex-row gap-3">
                                    <div class="form-group w-100">
                                        <label for="precio-proveedor">Precio Proveedor:</label>
                                        <input type="text" class="form-control" id="precio-proveedor">
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="precio-venta">Precio de Venta (Sugerido):</label>
                                        <input type="text" class="form-control" id="precio-venta">
                                    </div>
                                </div>
                                <div class="d-flex flex-row gap-3">
                                    <div class="form-group w-100">
                                        <label for="precio-referencial">¿Precio Referencial?</label>
                                        <input type="checkbox" class="form-check-input" id="precio-referencial">
                                        <input type="text" class="form-control mt-2" id="precio-referencial-valor" disabled>
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="maneja-inventario">Maneja Inventario:</label>
                                        <select class="form-select" id="maneja-inventario">
                                            <option selected>-- Selecciona --</option>
                                            <option value="1">Sí</option>
                                            <option value="2">No</option>
                                        </select>
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="producto-variable">Producto Variable:</label>
                                        <select class="form-select" id="producto-variable">
                                            <option selected>-- Selecciona --</option>
                                            <option value="1">Sí</option>
                                            <option value="2">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex flex-row gap-3">
                                    <div class="form-group w-100">
                                        <label for="stock-inicial">Stock Inicial:</label>
                                        <input type="text" class="form-control" id="stock-inicial">
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="stock-minimo">Stock Mínimo:</label>
                                        <input type="text" class="form-control" id="stock-minimo">
                                    </div>
                                    <div class="form-group w-100 hidden-field" id="bodega-field">
                                        <label for="bodega">Bodega:</label>
                                        <select class="form-select" id="bodega">
                                            <option selected>-- Selecciona Bodega --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="inventario-variable" role="tabpanel" aria-labelledby="inventario-variable-tab">
                            <div class="d-flex">
                                <table id="datatable_inventarioVariable" class="table table-striped w-50">
                                    <thead>
                                        <tr>
                                            <th class="centered">Atributo</th>
                                            <th class="centered">Valor</th>
                                            <th class="centered"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody_inventarioVariable"></tbody>
                                </table>

                                <table id="datatable_detalleInventario" class="table table-bordered table-striped table-hover w-50">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap">Atribuo</th>
                                            <th class="text-nowrap">SKU</th>
                                            <th class="text-nowrap">P. Proveedor</th>
                                            <th class="text-nowrap">P. de Venta</th>
                                            <th class="text-nowrap">P. Referencial</th>
                                            <th class="text-nowrap">Bodega</th>
                                            <th class="text-nowrap">Stock inicial</th>
                                            <th class="text-nowrap">ID variable</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody_detalleInventario"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

<script>
   
</script>