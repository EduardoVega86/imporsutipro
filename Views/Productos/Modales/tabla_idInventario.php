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
        height: 2px;
        /* Ajusta la altura según sea necesario */
        background-color: black;
        /* Cambia el color según sea necesario */
        margin: 0;
        /* Espaciado opcional alrededor de la línea */
        opacity: 1;
    }
</style>
<div class="modal fade" id="tabla_idInventarioModal" tabindex="-1" aria-labelledby="tabla_idInventarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tabla_idInventarioModalLabel">INVENTARIO VARIABLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <input type="hidden" id="id_productoIventario" name="id_productoIventario" value="0">
                <table id="datatable_tabla_idInventario" class="table table-striped w-100">
                    <thead>
                        <tr>
                            <th class="centered">Descripcion</th>
                            <th class="centered">Precio Proveedor</th>
                            <th class="centered">Precio de Venta</th>
                            <th class="centered">ID inventario</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody_tabla_idInventario"></tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>