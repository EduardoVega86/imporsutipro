let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
    //scrollX: "2000px",
    /* lengthMenu: [5, 10, 15, 20, 100, 200, 500], */
    columnDefs: [
        { className: "centered", targets: [0, 1, 2, 3, 4, 5,6,7,8] },
        /* { orderable: false, targets: [5, 6] }, */
        /* { searchable: false, targets: [1] } */
        //{ width: "50%", targets: [0] }
    ],
    pageLength: 10,
    destroy: true,
    language: {
        lengthMenu: "Mostrar _MENU_ registros por página",
        zeroRecords: "Ningún usuario encontrado",
        info: "Mostrando de _START_ a _END_ de un total de _TOTAL_ registros",
        infoEmpty: "Ningún usuario encontrado",
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

const initDataTable = async () => {
    if (dataTableIsInitialized) {
        dataTable.destroy();
    }

    await listNuevosPedidos();

    dataTable = $("#datatable_nuevosPedidos").DataTable(dataTableOptions);

    dataTableIsInitialized = true;
};

const listNuevosPedidos = async () => {
    try {
        const response = await fetch(""+SERVERURL+"pedidos/obtener_guias");
        const nuevosPedidos = await response.json();

        let content = ``;
        nuevosPedidos.forEach((nuevoPedido, index) => {
            content += `
                <tr>
                    <td>${nuevoPedido.numero_factura}</td>
                    <td>${nuevoPedido.fecha_factura}</td>
                    <td>
                        <div><strong>${nuevoPedido.nombre}</strong></div>
                        <div>${nuevoPedido.c_principal} y ${nuevoPedido.c_secundaria}</div>
                        <div>telf: ${nuevoPedido.telefono}</div>
                    </td>
                    <td>PAIS</td>
                    <td>${nuevoPedido.tienda}</td>
                    <td>${transporte_contet}</td>
                    <td>
                        <span class="w-100">${nuevoPedido.estado_nuevoPedido_sistema}</span>
                        <a class="w-100" href="https://wa.me/${formatPhoneNumber(nuevoPedido.telefono)}" style="font-size: 40px;" target="_blank"><box-icon type='logo' name='whatsapp-square' color="green"></box-icon></a>
                    </td>
                    <td>${nuevoPedido.impreso}</td>
                    <td>
                        <button class="btn btn-sm btn-primary"><i class="fa-solid fa-pencil"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>`;
        });
        document.getElementById('tableBody_nuevosPedidos').innerHTML = content;
    } catch (ex) {
        alert(ex);
    }
};

window.addEventListener("load", async () => {
    await initDataTable();
});