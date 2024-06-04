let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
    //scrollX: "2000px",
    lengthMenu: [5, 10, 15, 20, 100, 200, 500],
    columnDefs: [
        { className: "centered", targets: [0, 1, 2, 3, 4, 5, 6] },
        /* { orderable: false, targets: [5, 6] }, */
        /* { searchable: false, targets: [1] } */
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
            let transporte = guia.transporte;
            let transporte_contet='';
            if (transporte == 'SERVIENTREGA'){
                transporte_contet == '<span style="background-color: #28C839; color: white; padding: 5px;">SERVIENTREGA</span>';
            }else if (transporte == 'LAAR'){
                transporte_contet == '<span style="background-color: #F4DB08; color: white; padding: 5px;">LAAR</span>';
            }else if (transporte == 'SPEED'){
                transporte_contet == '<span style="background-color: #red; color: white; padding: 5px;">SPEED</span>';
            }else if (transporte == 'GINTRACOM'){
                transporte_contet == '<span style="background-color: #red; color: white; padding: 5px;">GINTRACOM</span>';
            }else {
                transporte_contet == '<span style="background-color: #F4DB08; color: white; padding: 5px;">Guia no enviada</span>';
            }
            content += `
                <tr>
                    <td>${guia.numero_factura}</td>
                    <td>${guia.fecha_factura}</td>
                    <td class="d-flex flex-column">
                    <span></span><strong> ${guia.nombre} </strong></span>
                    <span>${guia.c_principal} y ${guia.c_secundaria}</span>
                    <span>telf: ${guia.telefono}</span>
                    </td>
                    <td>PAIS</td>
                    <td>${guia.tienda}</td>
                    <td>${transporte_contet}</td>
                    <td class="d-flex flex-row">
                    <span>${guia.estado_guia_sistema}</span>
                    <a href="https://wa.me/${formatPhoneNumber(guia.telefono)}" style="font-size: 40px;" target="_blank"><i class="bx bxl-whatsapp-square" style="color: green"></i></a>
                    </td>
                    <td>${guia.impreso}</td>
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

function formatPhoneNumber(number) {
    // Eliminar caracteres no numéricos excepto el signo +
    number = number.replace(/[^\d+]/g, '');

    // Verificar si el número ya tiene el código de país +593
    if (/^\+593/.test(number)) {
        // El número ya está correctamente formateado con +593
        return number;
    } else if (/^593/.test(number)) {
        // El número tiene 593 al inicio pero le falta el +
        return '+' + number;
    } else {
        // Si el número comienza con 0, quitarlo
        if (number.startsWith('0')) {
            number = number.substring(1);
        }
        // Agregar el código de país +593 al inicio del número
        number = '+593' + number;
    }

    return number;
}