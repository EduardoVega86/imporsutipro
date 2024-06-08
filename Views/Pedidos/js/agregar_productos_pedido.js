let dataTableNuevosPedidos;
let dataTableNuevosPedidosIsInitialized = false;

function getParameterByName(name) {
    const url = new URL(window.location.href);
    return url.searchParams.get(name);
}
// Obtener el id_producto de la URL
const id_producto = getParameterByName('id_producto');
const sku = getParameterByName('sku');

const dataTableNuevosPedidosOptions = {
    //scrollX: "2000px",
    /* lengthMenu: [5, 10, 15, 20, 100, 200, 500], */
    columnDefs: [
        { className: "centered", targets: [0, 1, 2, 3, 4, 5, 6] },
        /* { orderable: false, targets: [5, 6] }, */
        /* { searchable: false, targets: [1] } */
        //{ width: "50%", targets: [0] }
    ],
    pageLength: 10,
    destroy: true,
    language: {
        lengthMenu: "Mostrar _MENU_ registros por página",
        zeroRecords: "Ningún pedido encontrado",
        info: "Mostrando de _START_ a _END_ de un total de _TOTAL_ registros",
        infoEmpty: "Ningún pedido encontrado",
        infoFiltered: "(filtrados desde _MAX_ registros totales)",
        search: "Buscar:",
        loadingRecords: "Cargando...",
        paginate: {
            first: "Primero",
            last: "Último",
            next: "Siguiente",
            previous: "Anterior"
        }
    }
};

const initDataTableNuevosPedidos = async () => {
    if (dataTableNuevosPedidosIsInitialized) {
        dataTableNuevosPedidos.destroy();
    }

    await listNuevosPedidos();

    dataTableNuevosPedidos = $("#datatable_nuevosPedidos").DataTable(dataTableNuevosPedidosOptions);

    dataTableNuevosPedidosIsInitialized = true;
};

const listNuevosPedidos = () => {
    // Crear una instancia de FormData
    let formData = new FormData();
    formData.append('sku', sku);  // Añadir el SKU al FormData

    $.ajax({
        url: SERVERURL + "pedidos/buscarProductosBodega/" + id_producto,
        type: 'POST',  // Cambiar a POST para enviar FormData
        data: formData,
        processData: false,  // No procesar los datos
        contentType: false,  // No establecer ningún tipo de contenido
        success: function(nuevosPedidos) {
            let content = ``;
            nuevosPedidos.forEach((nuevoPedido, index) => {
                content += `
                    <tr>
                        <td>${nuevoPedido.image_path}</td>
                        <td>${nuevoPedido.id_producto}</td>
                        <td>${nuevoPedido.nombre_producto}</td>
                        <td>${nuevoPedido.stock_inicial}</td>
                        <td><input type="number" class="form-control" value="1" min="1" id="cantidad_${index}"></td>
                        <td>${nuevoPedido.pvp}</td>
                        <td>
                            <button class="btn btn-sm btn-success"><i class="fa-solid fa-pencil"></i></button>
                        </td>
                    </tr>`;
            });
            document.getElementById('tableBody_nuevosPedidos').innerHTML = content;
            $('#nuevosPedidosModal').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });
};



//abrir modal
function buscar_productos_nuevoPedido(){
    $('#nuevosPedidosModal').modal('show');
}

window.addEventListener("load", async () => {
    await initDataTableNuevosPedidos();
});
