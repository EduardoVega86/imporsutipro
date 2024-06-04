let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
    //scrollX: "2000px",
    lengthMenu: [5, 10, 15, 20, 100, 200, 500],
    columnDefs: [
        { className: "centered", targets: [0, 1, 2, 3, 4, 5, 6] },
        { orderable: false, targets: [5, 6] },
        { searchable: false, targets: [1] }
        //{ width: "50%", targets: [0] }
    ],
    pageLength: 3,
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

    await listGuias();

    dataTable = $("#datatable_guias").DataTable(dataTableOptions);

    dataTableIsInitialized = true;
};

const listGuias = async () => {
    try {
        const response = await fetch("https://new.imporsuitpro.com/pedidos/obtener_guias");
        const guias = await response.json();

        let content = ``;
        guias.forEach((guia, index) => {
            content += `
                <tr>
                    <td>${guia.numero_factura}</td>
                    <td>${guia.fecha}</td>
                    <td>
                    <strong> ${guia.nombre} </strong>
                    ${guia.c_principal} y ${guia.c_secundaria}
                    telf: ${guia.telefono}
                    </td>
                    <td>PAIS</td>
                    <td>${guia.tienda}</td>
                    <td>${guia.transporte}</td>
                    <td>${guia.estado_guia_sistema}</i></td>
                    <td>${guia.impreso}</i></td>
                    <td>
                        <button class="btn btn-sm btn-primary"><i class="fa-solid fa-pencil"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>`;
        });
        tableBody_guias.innerHTML = content;
    } catch (ex) {
        alert(ex);
    }
};

window.addEventListener("load", async () => {
    await initDataTable();
});