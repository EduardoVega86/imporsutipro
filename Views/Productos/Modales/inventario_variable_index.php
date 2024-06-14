<style>
    .tag {
        display: inline-block;
        padding: 4px;
        background-color: #007bff;
        color: white;
        border-radius: 0.25rem;
        margin-right: 0.5em;
        margin-top: 2px;
    }

    .tag .remove-tag {
        margin-left: 0.5em;
        cursor: pointer;
    }

    .vertical-line {
        border-left: 2px solid black;
        /* Cambia el grosor y el color según sea necesario */
        margin: 0 20px;
        /* Espaciado opcional alrededor de la línea */
    }
    .custom-hr {
            border: 0;
            height: 2px; /* Ajusta la altura según sea necesario */
            background-color: black; /* Cambia el color según sea necesario */
            margin: 20px 0; /* Espaciado opcional alrededor de la línea */
        }
</style>
<div class="modal fade" id="inventario_variableModal" tabindex="-1" aria-labelledby="inventario_variableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inventario_variableModalLabel">INVENTARIO VARIABLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column">
                    <div class="d-flex flex-row">
                        <input type="hidden" id="id_productoVariable" name="id_productoVariable" value="0">
                        <table id="datatable_inventarioVariable" class="table table-striped w-100">
                            <thead>
                                <tr>
                                    <th class="centered">Atributo</th>
                                    <th class="centered">Valor</th>
                                    <th class="centered"></th>
                                </tr>
                            </thead>
                            <tbody id="tableBody_inventarioVariable"></tbody>
                        </table>
                        <div class="vertical-line"></div>
                        <table id="datatable_detalleInventario" class="table table-bordered table-striped table-hover w-100">
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
                    <hr class="custom-hr">
                    <div id="inputs_guardarAtributos">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>