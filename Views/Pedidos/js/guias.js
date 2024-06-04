let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
    //scrollX: "2000px",
    lengthMenu: [5, 10, 15, 20, 100, 200, 500],
    columnDefs: [
        { className: "centered", targets: [0, 1, 2, 3, 4, 5,6,7,8] },
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
            console.log(transporte);
            let transporte_contet = '';
            if (transporte == 'SERVIENTREGA') {
                transporte_contet = '<span style="background-color: #28C839; color: white; padding: 5px; border-radius: 0.3rem;">SERVIENTREGA</span>';
            } else if (transporte == 'LAAR') {
                transporte_contet = '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">LAAR</span>';
            } else if (transporte == 'SPEED') {
                transporte_contet = '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">SPEED</span>';
            } else if (transporte == 'GINTRACOM') {
                transporte_contet = '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">GINTRACOM</span>';
            } else {
                transporte_contet = '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">Guia no enviada</span>';
            }
            content += `
                <tr>
                    <td>${guia.numero_factura}</td>
                    <td>${guia.fecha_factura}</td>
                    <td>
                        <div><strong>${guia.nombre}</strong></div>
                        <div>${guia.c_principal} y ${guia.c_secundaria}</div>
                        <div>telf: ${guia.telefono}</div>
                    </td>
                    <td>PAIS</td>
                    <td>${guia.tienda}</td>
                    <td>${transporte_contet}</td>
                    <td>
                        <span class="w-100">${guia.estado_guia_sistema}</span>
                        <a class="w-100" href="https://wa.me/${formatPhoneNumber(guia.telefono)}" style="font-size: 40px;" target="_blank"><box-icon type='logo' name='whatsapp-square' color="green"></box-icon></a>
                    </td>
                    <td>${guia.impreso}</td>
                    <td>
                        <button class="btn btn-sm btn-primary"><i class="fa-solid fa-pencil"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>`;
        });
        document.getElementById('tableBody_guias').innerHTML = content;
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