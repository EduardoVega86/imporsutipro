<div class="modal fade" id="modalFiltros" tabindex="-1" aria-labelledby="modalFiltrosLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFiltrosLabel">
                    <i class="fas fa-sliders-h"></i> Filtros de búsqueda avanzada
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row g-3">
                        <!-- Rango de fechas -->
                        <div class="col-md-6">
                            <label for="daterange" class="form-label">Rango de fechas</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="daterange">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </div>
                        </div>

                        <!-- Impresiones -->
                        <div class="col-md-6">
                            <label for="impresion" class="form-label">Impresiones</label>
                            <select name="impresion" class="form-control" id="impresion">
                                <option value="">Todas</option>
                                <option value="1">Impresas</option>
                                <option value="0">No impresas</option>
                            </select>
                        </div>

                        <!-- Despachos -->
                        <div class="col-md-6">
                            <label for="despachos" class="form-label">Despachos</label>
                            <select name="despachos" class="form-control" id="despachos">
                                <option value="">Todas</option>
                                <option value="2">Despachados</option>
                                <option value="1">No despachados</option>
                                <option value="3">Devueltos</option>
                                <option value="4">No devueltos</option>
                            </select>
                        </div>

                        <!-- Proveedor / Dropshipper -->
                        <div class="col-md-6">
                            <label for="tienda_q" class="form-label">Proveedor / Dropshipper</label>
                            <select id="tienda_q" class="form-control">
                                <option value="">Selecciona una opción</option>
                                <option value="1">Dropshipper</option>
                                <option value="0">Local</option>
                            </select>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-6">
                            <label for="estado_q" class="form-label">Estado</label>
                            <select name="estado_q" class="form-control" id="estado_q">
                                <option value="">Seleccione estado</option>
                                <option value="generada">Generada / Por recolectar</option>
                                <option value="en_transito">En tránsito / Procesamiento</option>
                                <option value="zona_entrega">Zona de entrega</option>
                                <option value="entregada">Entregadas</option>
                                <option value="novedad">Novedad</option>
                                <option value="devolucion">Devolución</option>
                            </select>
                        </div>

                        <!-- Transportadora -->
                        <div class="col-md-6">
                            <label for="transporte" class="form-label">Transportadora</label>
                            <select name="transporte" id="transporte" class="form-control">
                                <option value="">Seleccione transportadora</option>
                                <option value="LAAR">Laar</option>
                                <option value="SPEED">Speed</option>
                                <option value="SERVIENTREGA">Servientrega</option>
                                <option value="GINTRACOM">Gintracom</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnAplicarFiltros" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Aplicar Filtros
                </button>
                <div id="modalFilterLoader" style="display: none;">
                    <div class="spinner-border text-primary" role="status" style="width: 1.5rem; height: 1.5rem;">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>