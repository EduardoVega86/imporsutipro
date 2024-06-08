let dataTableNuevosPedidos;
let dataTableNuevosPedidosIsInitialized = false;

const dataTableNuevosPedidosOptions = {
    //scrollX: "2000px",
    /* lengthMenu: [5, 10, 15, 20, 100, 200, 500], */
    columnDefs: [
        { className: "centered", targets: [0, 1, 2, 3, 4, 5, 6, 7, 8] },
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

const listNuevosPedidos = async () => {
    try {
        const response = await fetch(""+SERVERURL+"pedidos/obtener_guias");
        const nuevosPedidos = await response.json();

        let content = ``;
        nuevosPedidos.forEach((nuevoPedido, index) => {
            content += `
                <tr>
                    <td>${nuevoPedido.imagen}</td>
                    <td>${nuevoPedido.codigo}</td>
                    <td>${nuevoPedido.nombre}</td>
                    <td>${nuevoPedido.stock}</td>
                    <td><input type="number" class="form-control" value="1" min="1" id="cantidad_${index}"></td>
                    <td>${nuevoPedido.codigo}</td>
                    
                    <td>
                        <button class="btn btn-sm btn-succes"><i class="fa-solid fa-pencil"></i></button>
                    </td>
                </tr>`;
        });
        document.getElementById('tableBody_nuevosPedidos').innerHTML = content;
    } catch (ex) {
        alert(ex);
    }
};

window.addEventListener("load", async () => {
    await initDataTableNuevosPedidos();
});
