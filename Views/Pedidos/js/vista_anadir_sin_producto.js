let dataTablePedidosSinProducto;
let dataTablePedidosSinProductoIsInitialized = false;
let filtroProductos = 1; // 1: Propios | 2: Bodegas | 3: Privados (Valor inicial: Propios)
let bodega_seleccionada = 0;

const dataTablePedidosSinProductoOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5] },
    { orderable: false, targets: 0 },
  ],
  pageLength: 5,
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
    formData.append("bodegas", bodega_seleccionada);

    const response = await fetch(
      `${SERVERURL}productos/obtener_productos_bps`,
      {
        method: "POST",
        body: formData,
      }
    );

    const data = await response.json();

    if (data.status === 500) {
      Swal.fire({
        icon: "error",
        title: data.title || "Error",
        text: data.message || "Ocurrió un error inesperado",
      });

      document.getElementById("tableBody_pedidos_sin_producto").innerHTML = "";
      return;
    }

    let content = ``;
    data.data.forEach((pedido) => {
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
    Swal.fire({
      icon: "error",
      title: "Error de conexión",
      text: "No se pudo conectar con el servidor. Intenta nuevamente.",
    });

    document.getElementById("tableBody_pedidos_sin_producto").innerHTML = "";
  }
};

// 🚀 **Eventos para cambiar entre Propios, Bodegas y Privados**
document.getElementById("btnPropios").addEventListener("click", () => {
  filtroProductos = 1;
  bodega_seleccionada = 0;
  ocultarSelectBodegas();
  actualizarBotones();
  initDataTablePedidosSinProducto();
});

document.getElementById("btnBodegas").addEventListener("click", async () => {
  filtroProductos = 2;
  mostrarSelectBodegas();
  actualizarBotones();
  await cargarBodegas(); // 🔹 Llama a la API para llenar el select de bodegas
});

document.getElementById("btnPrivados").addEventListener("click", () => {
  filtroProductos = 3;
  bodega_seleccionada = 0;
  ocultarSelectBodegas();
  actualizarBotones();
  initDataTablePedidosSinProducto();
});

// 🚀 **Evento para cambiar la bodega seleccionada**
document.getElementById("selectBodega").addEventListener("change", () => {
  bodega_seleccionada = document.getElementById("selectBodega").value;
  initDataTablePedidosSinProducto();
});

// 🚀 **Mostrar u ocultar el select de bodegas**
const mostrarSelectBodegas = () => {
  document.getElementById("bodegaContainer").style.display = "block";
};

const ocultarSelectBodegas = () => {
  document.getElementById("bodegaContainer").style.display = "none";
};

// 🚀 **Cargar las bodegas desde la API**
const cargarBodegas = async () => {
  try {
    const response = await fetch(`${SERVERURL}productos/obtener_bodegas_psp`);
    const data = await response.json();

    if (data.status === 500) {
      Swal.fire({
        icon: "error",
        title: data.title || "Error",
        text: data.message || "No se pudieron cargar las bodegas",
      });
      return;
    }

    const selectBodega = document.getElementById("selectBodega");
    selectBodega.innerHTML = '<option value="0">Seleccione una bodega</option>'; // Resetear opciones

    data.data.forEach((bodega) => {
      selectBodega.innerHTML += `<option value="${bodega.id_bodega}">${bodega.nombre_bodega}</option>`;
    });
  } catch (ex) {
    Swal.fire({
      icon: "error",
      title: "Error de conexión",
      text: "No se pudo conectar con el servidor para obtener las bodegas.",
    });
  }
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
