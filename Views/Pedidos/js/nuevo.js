let dataTableNuevosPedidos;
let dataTableNuevosPedidosIsInitialized = false;

const dataTableNuevosPedidosOptions = {
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
        zeroRecords: "Ningún producto encontrado",
        info: "Mostrando de _START_ a _END_ de un total de _TOTAL_ registros",
        infoEmpty: "Ningún producto encontrado",
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

// Inicializar el DataTable para los nuevos pedidos
const initDataTableNuevosPedidos = async () => {
    if (dataTableNuevosPedidosIsInitialized) {
        dataTableNuevosPedidos.destroy();
    }

    dataTableNuevosPedidos = $("#datatable_nuevosPedidos").DataTable(dataTableNuevosPedidosOptions);

    dataTableNuevosPedidosIsInitialized = true;
};

// Función para buscar productos y mostrar el modal
const buscar_productos_nuevoPedido = async (id_producto, sku) => {
    const formData = new FormData();
    formData.append('sku', sku);

    try {
        const response = await fetch(`https://new.imporsuitpro.com/pedidos/buscarProductosBodega/${id_producto}`, {
            method: 'POST',
            body: formData
        });
        const productos = await response.json();

        let content = ``;
        productos.forEach((producto, index) => {
            content += `
                <tr>
                    <td><img src="${producto.imagen}" alt="Imagen del producto" style="max-width: 50px;"></td>
                    <td>${producto.codigo}</td>
                    <td>${producto.nombre}</td>
                    <td>${producto.stock}</td>
                    <td><input type="number" class="form-control" value="1" min="1" id="cantidad_${index}"></td>
                    <td>${producto.precio}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="agregarProductoPedido(${producto.id})">Agregar</button>
                    </td>
                </tr>`;
        });

        document.getElementById('tableBody_nuevosPedidos').innerHTML = content;
        await initDataTableNuevosPedidos();
        $('#nuevosPedidosModal').modal('show');
    } catch (error) {
        console.error('Error al buscar productos:', error);
        alert('Hubo un problema al buscar los productos');
    }
};

// Función para agregar producto al pedido
const agregarProductoPedido = (idProducto) => {
    // Aquí puedes implementar la lógica para agregar el producto al pedido
    console.log(`Agregar producto con ID: ${idProducto}`);
};

window.addEventListener("load", async () => {
    await initDataTableNuevoPedido();
});

