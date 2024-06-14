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
<div class="modal fade" id="seleccionProdcutoAtributoModal" tabindex="-1" aria-labelledby="seleccionProdcutoAtributoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="seleccionProdcutoAtributoModalLabel">INVENTARIO VARIABLE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <input type="hidden" id="id_productoSeleccionado" name="id_productoSeleccionado" value="0">
                <table id="datatable_seleccionProductoAtributo" class="table table-striped w-100">
                    <thead>
                        <tr>
                            <th class="centered">Atributo</th>
                            <th class="centered">Valor</th>
                            <th class="centered"></th>
                        </tr>
                    </thead>
                    <tbody id="tableBody_seleccionProductoAtributo"></tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>