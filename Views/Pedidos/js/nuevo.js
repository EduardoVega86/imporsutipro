let dataTableNuevoPedido;
let dataTableNuevoPedidoIsInitialized = false;

const dataTableNuevoPedidoOptions = {
    paging: false,
    searching: false,
    info: false,
    lengthChange: false,
    destroy: true,
    autoWidth: false,
    columnDefs: [
        { className: "centered", targets: [0, 1, 2, 3, 4, 5, 6] },
    ],
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

const initDataTableNuevoPedido = async () => {
    if (dataTableNuevoPedidoIsInitialized) {
        dataTableNuevoPedido.destroy();
    }

    await listNuevoPedido();

    dataTableNuevoPedido = $("#datatable_nuevoPedido").DataTable(dataTableNuevoPedidoOptions);

    dataTableNuevoPedidoIsInitialized = true;
};

const listNuevoPedido = async () => {
    try {
        const response = await fetch(""+SERVERURL+"pedidos/buscarTmp");
        const nuevosPedidos = await response.json();

        let content = ``;
        nuevosPedidos.forEach((nuevoPedido, index) => {
            const precio = parseFloat(nuevoPedido.precio_tmp);
            const descuento = parseFloat(nuevoPedido.desc_tmp);
            const precioFinal = precio - (precio * (descuento / 100));

            content += `
                <tr>
                    <td>${nuevoPedido.id_tmp}</td>
                    <td>${nuevoPedido.cantidad_tmp}</td>
                    <td>${nuevoPedido.nombre_producto}</td>
                    <td><input type="text" id="precio_nuevoPedido_${index}" class="form-control" value="${precio}"></td>
                    <td><input type="text" id="descuento_nuevoPedido_${index}" class="form-control" value="${descuento}"></td>
                    <td><span id="precioFinal_nuevoPedido_${index}">${precioFinal.toFixed(2)}</span></td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="eliminar_nuevoPedido(${nuevoPedido.id_tmp})"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>`;
        });
        document.getElementById('tableBody_nuevoPedido').innerHTML = content;
    } catch (ex) {
        alert(ex);
    }
};

function eliminar_nuevoPedido(id) {
    $.ajax({
        type: "POST",
        url: SERVERURL + "pedidos/eliminarTmp/"+id,
        dataType: 'json', // Asegurarse de que la respuesta se trata como JSON
        success: function (response) {
            // Mostrar alerta de éxito
            if (response.status == 500) {
                toastr.error('EL PRODUCTO NO SE ELIMINADO CORRECTAMENTE', 'NOTIFICACIÓN', { positionClass: 'toast-bottom-center' });
            } else if (response.status == 200){
                toastr.success('PRODUCTO ELIMINADO CORRECTAMENTE', 'NOTIFICACIÓN', { positionClass: 'toast-bottom-center' });
            }

            // Recargar la DataTable
            initDataTableNuevoPedido();
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX:", error);
            alert("Hubo un problema al eliminar la categoría");
        },
    });
}
window.addEventListener("load", async () => {
    await initDataTableNuevoPedido();
});

