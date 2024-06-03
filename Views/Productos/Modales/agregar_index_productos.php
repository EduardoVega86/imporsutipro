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
                    <li class="nav-item hidden-tab" role="presentation">
                        <button class="nav-link" id="inventario-variable-tab" data-bs-toggle="tab" data-bs-target="#inventario-variable" type="button" role="tab" aria-controls="inventario-variable" aria-selected="false"><strong>Inventario Variable</strong></button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="datos-basicos" role="tabpanel" aria-labelledby="datos-basicos-tab">
                        <form>
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
                                                <option selected>-- Selecciona --</option>
                                                <option value="1">Categoría 1</option>
                                                <option value="2">Categoría 2</option>
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
                                            <label for="proveedor">Proveedor:</label>
                                            <select class="form-select" id="proveedor">
                                                <option selected>-- Selecciona --</option>
                                                <option value="1">Proveedor 1</option>
                                                <option value="2">Proveedor 2</option>
                                            </select>
                                        </div>
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
                        </form>
                    </div>
                    <div class="tab-pane fade" id="precios-stock" role="tabpanel" aria-labelledby="precios-stock-tab">
                        <form>
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
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="inventario-variable" role="tabpanel" aria-labelledby="inventario-variable-tab">
                        <form>
                            <div class="form-group">
                                <label for="inventario-variable-input">Inventario Variable:</label>
                                <input type="text" class="form-control" id="inventario-variable-input">
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


<script>
        document.addEventListener('DOMContentLoaded', function () {
            const productoVariableSelect = document.getElementById('producto-variable');
            const inventarioVariableTab = document.getElementById('inventario-variable-tab');
            const inventarioVariableContent = document.getElementById('inventario-variable');

            productoVariableSelect.addEventListener('change', function () {
                if (productoVariableSelect.value === '1') {
                    inventarioVariableTab.classList.remove('hidden-tab');
                } else {
                    inventarioVariableTab.classList.add('hidden-tab');
                    // If switching to "No", switch tab to another available tab
                    document.getElementById('datos-basicos-tab').click();
                }
            });
        });
    </script>