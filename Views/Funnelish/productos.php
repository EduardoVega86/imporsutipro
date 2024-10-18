<?php require_once './Views/templates/header.php'; ?>

<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Productos vinculados a funnelish</h2>

        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-hover"> -->
            <table id="datatable_guias" class="table table-striped">
                <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                <thead>
                    <tr>
                        <th class="centered">Id</th>
                        <th class="centered">Producto</th>
                        <th class="centered">Codigo Funnelish</th>
                        <th class="centered">Codigo Producto</th>
                        <th colspan="2" class="centered">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableProductos">

                </tbody>
            </table>
        </div>
    </div>

    <!-- modal para editar -->
    <div class="modal fade" id="modalProducto" tabindex="-1" role="dialog" aria-labelledby="modalProductoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="formEditar">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalProductoLabel">Editar Producto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="id">Id</label>
                            <input type="text" class="form-control" id="id" name="id" readonly>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="producto">Producto</label>
                                <input disabled type="text" class="form-control" id="producto" name="producto">

                            </div>
                            <div class="form-group">
                                <label for="codigo_funnelish">Codigo Funnelish</label>
                                <input type="text" class="form-control" id="codigo_funnelish" name="codigo_funnelish">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Funnelish/js/productos.js"></script>

<?php require_once './Views/templates/footer.php'; ?>