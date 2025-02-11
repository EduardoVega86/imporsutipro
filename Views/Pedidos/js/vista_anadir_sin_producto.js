let dataTablePedidosSinProducto;
let dataTablePedidosSinProductoIsInitialized = false;
let filtroProductos = 1; // 1: Propios | 2: Bodegas | 3: Privados (Valor inicial: Propios)

const dataTablePedidosSinProductoOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5] },
    { orderable: false, targets: 0 },
  ],
  pageLength: 10,
  destroy: true,
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
      previous: "Anterior",
    },
  },
};

const initDataTablePedidosSinProducto = async () => {
  if (dataTablePedidosSinProductoIsInitialized) {
    dataTablePedidosSinProducto.destroy();
  }

  await listPedidosSinProducto();

  dataTablePedidosSinProducto = $("#datatable_pedidos_sin_producto").DataTable(
    dataTablePedidosSinProductoOptions
  );
  dataTablePedidosSinProductoIsInitialized = true;
};

const listPedidosSinProducto = async () => {
  try {
    const formData = new FormData();
    formData.append("filtro", filtroProductos); // 🔹 Se envía el filtro a la API (1, 2 o 3)

    const response = await fetch(
      `${SERVERURL}productos/obtener_productos_bps`,
      {
        method: "POST",
        body: formData,
      }
    );

    const pedidosSinProducto = await response.json();
    let content = ``;

    pedidosSinProducto.forEach((pedido) => {
      const enlace_imagen = obtenerURLImagen(pedido.image_path, SERVERURL);
      let cargar_imagen = pedido.image_path
        ? `<img src="${enlace_imagen}" class="icon-button" alt="Imagen" width="50px">`
        : `<i class="bx bxs-camera-plus"></i>`;

      content += `
        <tr>
          <td>${pedido.sku}</td>
          <td>${pedido.nombre_producto}</td>
          <td>${pedido.pcp}</td>
          <td>${pedido.pvp}</td>
          <td>${cargar_imagen}</td>
          <td>
            <button class="btn btn-sm btn-danger" onclick="eliminar_producto(${pedido.id_producto})">
              <i class="fa-solid fa-trash-can"></i> Borrar
            </button>
          </td>
        </tr>`;
    });

    document.getElementById("tableBody_pedidos_sin_producto").innerHTML =
      content;
  } catch (ex) {
    alert("Error al cargar los productos: " + ex);
  }
};

// 🚀 **Eventos para cambiar entre Propios, Bodegas y Privados**
document.getElementById("btnPropios").addEventListener("click", () => {
  filtroProductos = 1; // Se establece como "Propios"
  actualizarBotones();
  initDataTablePedidosSinProducto();
});

document.getElementById("btnBodegas").addEventListener("click", () => {
  filtroProductos = 2; // Se establece como "Bodegas"
  actualizarBotones();
  initDataTablePedidosSinProducto();
});

document.getElementById("btnPrivados").addEventListener("click", () => {
  filtroProductos = 3; // Se establece como "Privados"
  actualizarBotones();
  initDataTablePedidosSinProducto();
});

const actualizarBotones = () => {
  document
    .getElementById("btnPropios")
    .classList.toggle("active", filtroProductos === 1);
  document
    .getElementById("btnBodegas")
    .classList.toggle("active", filtroProductos === 2);
  document
    .getElementById("btnPrivados")
    .classList.toggle("active", filtroProductos === 3);

  document
    .getElementById("btnPropios")
    .classList.toggle("btn-primary", filtroProductos === 1);
  document
    .getElementById("btnBodegas")
    .classList.toggle("btn-primary", filtroProductos === 2);
  document
    .getElementById("btnPrivados")
    .classList.toggle("btn-primary", filtroProductos === 3);

  document
    .getElementById("btnPropios")
    .classList.toggle("btn-secondary", filtroProductos !== 1);
  document
    .getElementById("btnBodegas")
    .classList.toggle("btn-secondary", filtroProductos !== 2);
  document
    .getElementById("btnPrivados")
    .classList.toggle("btn-secondary", filtroProductos !== 3);
};

// 🚀 **Inicialización al cargar la página**
window.addEventListener("load", async () => {
  await initDataTablePedidosSinProducto();
});

function obtenerURLImagen(imagePath, serverURL) {
  // Verificar si el imagePath no es null
  if (imagePath) {
    // Verificar si el imagePath ya es una URL completa
    if (imagePath.startsWith("http://") || imagePath.startsWith("https://")) {
      // Si ya es una URL completa, retornar solo el imagePath
      return imagePath;
    } else {
      // Si no es una URL completa, agregar el serverURL al inicio
      return `${serverURL}${imagePath}`;
    }
  } else {
    // Manejar el caso cuando imagePath es null
    console.error("imagePath es null o undefined");
    return null; // o un valor por defecto si prefieres
  }
}
