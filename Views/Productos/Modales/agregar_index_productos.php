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
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="datos-basicos" role="tabpanel" aria-labelledby="datos-basicos-tab">
                        <form>
                            <div class="form-group">
                                <label for="codigo">Código:</label>
                                <input type="text" class="form-control" id="codigo" value="10088">
                            </div>
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" class="form-control" id="nombre">
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripción:</label>
                                <textarea class="form-control" id="descripcion"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="categoria">Categoría:</label>
                                <select class="form-select" id="categoria">
                                    <option selected>-- Selecciona --</option>
                                    <option value="1">Categoría 1</option>
                                    <option value="2">Categoría 2</option>
                                </select>
                            </div>
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
                            <div class="form-group">
                                <label>Formato:</label>
                                <div class="d-flex">
                                    <img src="formato1.png" alt="Formato 1" class="me-2">
                                    <img src="formato2.png" alt="Formato 2">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="precios-stock" role="tabpanel" aria-labelledby="precios-stock-tab">
                        <form>
                            <div class="form-group">
                                <label for="costo">Costo:</label>
                                <input type="text" class="form-control" id="costo">
                            </div>
                            <div class="form-group">
                                <label for="utilidad">Utilidad %:</label>
                                <input type="text" class="form-control" id="utilidad">
                            </div>
                            <div class="form-group">
                                <label for="precio-proveedor">Precio Proveedor:</label>
                                <input type="text" class="form-control" id="precio-proveedor">
                            </div>
                            <div class="form-group">
                                <label for="precio-venta">Precio de Venta (Sugerido):</label>
                                <input type="text" class="form-control" id="precio-venta">
                            </div>
                            <div class="form-group">
                                <label for="precio-referencial">¿Precio Referencial?</label>
                                <input type="checkbox" class="form-check-input" id="precio-referencial">
                                <input type="text" class="form-control mt-2" id="precio-referencial-valor" disabled>
                            </div>
                            <div class="form-group">
                                <label for="maneja-inventario">Maneja Inventario:</label>
                                <select class="form-select" id="maneja-inventario">
                                    <option selected>-- Selecciona --</option>
                                    <option value="1">Sí</option>
                                    <option value="2">No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="producto-variable">Producto Variable:</label>
                                <select class="form-select" id="producto-variable">
                                    <option selected>-- Selecciona --</option>
                                    <option value="1">Sí</option>
                                    <option value="2">No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="stock-inicial">Stock Inicial:</label>
                                <input type="text" class="form-control" id="stock-inicial">
                            </div>
                            <div class="form-group">
                                <label for="stock-minimo">Stock Mínimo:</label>
                                <input type="text" class="form-control" id="stock-minimo">
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
