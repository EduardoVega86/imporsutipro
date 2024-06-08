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
</style>

<div class="modal fade" id="nuevosPedidosModal" tabindex="-1" aria-labelledby="nuevosPedidosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevosPedidosModalLabel">AGREGAR PRODUCTOS A PEDIDO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <!-- <table class="table table-bordered table-striped table-hover"> -->
                    <table id="datatable_nuevosPedidos" class="table table-striped">
                        <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                        <thead>
                            <tr>
                                <th class="centered">Imagen</th>
                                <th class="centered">Cod.</th>
                                <th class="centered">Productos</th>
                                <th class="centered">Stock</th>
                                <th class="centered">Cantidad</th>
                                <th class="centered">Precio</th>
                                <th class="centered"></th>
                            </tr>
                        </thead>
                        <tbody id="tableBody_nuevosPedidos"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>