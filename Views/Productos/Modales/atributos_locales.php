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
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ATRIBUTO</th>
                            <th>VALOR</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>TALLA</td>
                            <td>
                                <div class="tag">
                                    WFEWED <span class="remove-tag">x</span>
                                </div>
                            </td>
                            <td><input type="text" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <td>COLOR</td>
                            <td><input type="text" class="form-control"></td>
                            <td><input type="text" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <td>MARCA</td>
                            <td><input type="text" class="form-control"></td>
                            <td><input type="text" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <td>MODELO</td>
                            <td><input type="text" class="form-control"></td>
                            <td><input type="text" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <td>MATERIAL</td>
                            <td><input type="text" class="form-control"></td>
                            <td><input type="text" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <td>CAPACIDAD</td>
                            <td><input type="text" class="form-control"></td>
                            <td><input type="text" class="form-control" value=""></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.remove-tag').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    btn.parentElement.remove();
                });
            });
        });
    </script>