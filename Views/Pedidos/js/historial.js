let dataTableHistorial;
let dataTableHistorialIsInitialized = false;

const dataTableHistorialOptions = {
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

const initDataTableHistorial = async () => {
    if (dataTableHistorialIsInitialized) {
        dataTableHistorial.destroy();
    }

    await listHistorialPedidos();

    dataTableHistorial = $("#datatable_historialPedidos").DataTable(dataTableHistorialOptions);

    dataTableHistorialIsInitialized = true;
};

const listHistorialPedidos = async () => {
    try {
        const response = await fetch(""+SERVERURL+"pedidos/cargar_pedidos");
        const historialPedidos = await response.json();

        let content = ``;
        historialPedidos.forEach((historialPedido, index) => {
            let transporte = historialPedido.transporte;
            console.log(transporte);
            let transporte_content = '';
            if (transporte == 'SERVIENTREGA') {
                transporte_content = '<span style="background-color: #28C839; color: white; padding: 5px; border-radius: 0.3rem;">SERVIENTREGA</span>';
            } else if (transporte == 'LAAR') {
                transporte_content = '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">LAAR</span>';
            } else if (transporte == 'SPEED') {
                transporte_content = '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">SPEED</span>';
            } else if (transporte == 'GINTRACOM') {
                transporte_content = '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">GINTRACOM</span>';
            } else {
                transporte_content = '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">Guia no enviada</span>';
            }
            content += `
                <tr>
                    <td>${historialPedido.numero_factura}</td>
                    <td>${historialPedido.fecha_factura}</td>
                    <td>
                        <div><strong>${historialPedido.nombre}</strong></div>
                        <div>${historialPedido.c_principal} y ${historialPedido.c_secundaria}</div>
                        <div>telf: ${historialPedido.telefono}</div>
                    </td>
                    <td>PAIS</td>
                    <td>${historialPedido.tienda}</td>
                    <td>${transporte_content}</td>
                    <td>
                        <span class="w-100">${historialPedido.estado_guia_sistema}</span>
                        <a class="w-100" href="https://wa.me/${formatPhoneNumber(historialPedido.telefono)}" style="font-size: 40px;" target="_blank"><box-icon type='logo' name='whatsapp-square' color="green"></box-icon></a>
                    </td>
                    <td>${historialPedido.impreso}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="boton_editarPedido(${historialPedido.id_factura})"><i class="fa-solid fa-pencil"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>`;
        });
        document.getElementById('tableBody_historialPedidos').innerHTML = content;
    } catch (ex) {
        alert(ex);
    }
};

function boton_editarPedido(id){
    window.location.href = '' + SERVERURL + 'Pedidos/editar/'+id;
}


window.addEventListener("load", async () => {
    await initDataTableHistorial();
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