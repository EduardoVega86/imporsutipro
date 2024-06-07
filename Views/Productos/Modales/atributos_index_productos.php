<style>
    .tag {
        display: inline-block;
        padding: 0.25em 0.5em;
        background-color: #007bff;
        color: white;
        border-radius: 0.25rem;
        margin-right: 0.5em;
    }

    .tag .remove-tag {
        margin-left: 0.5em;
        cursor: pointer;
    }
</style>

<div class="modal fade" id="atributosModal" tabindex="-1" aria-labelledby="atributosModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="atributosModalLabel">CARACTERÍSTICAS DE PRODUCTOS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <!-- <table class="table table-bordered table-striped table-hover"> -->
                    <table id="datatable_atributos" class="table table-striped">
                        <!-- <caption>
                    DataTable.js Demo
                </caption> -->
                        <thead>
                            <tr>
                                <th class="centered">Atributo</th>
                                <th class="centered">Valor</th>
                                <th class="centered"></th>
                            </tr>
                        </thead>
                        <tbody id="tableBody_atributos"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>