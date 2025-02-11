let dataTablePedidosSinProducto;
let dataTablePedidosSinProductoIsInitialized = false;
let filtroProductos = 1; // 1: Propios | 2: Bodegas | 3: Privados
let bodega_seleccionada = 0;
let productosSeleccionados = [];

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
    formData.append("filtro", filtroProductos);
    formData.append("id_bodega", bodega_seleccionada);

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
            <input type="checkbox" class="checkProducto" data-id="${pedido.id_inventario}">
          </td>
        </tr>`;
    });

    document.getElementById("tableBody_pedidos_sin_producto").innerHTML =
      content;

    // Evento para capturar los cambios en los checkboxes
    $(".checkProducto").change(function () {
      let id = $(this).data("id");

      if ($(this).is(":checked")) {
        // Agregar al array si no existe
        if (!productosSeleccionados.includes(id)) {
          productosSeleccionados.push(id);
        }
      } else {
        // Eliminar del array si se deselecciona
        productosSeleccionados = productosSeleccionados.filter(
          (item) => item !== id
        );
      }

      console.log("Productos seleccionados:", productosSeleccionados); // Mostrar en consola

      // Deshabilitar o habilitar botones y select
      toggleBotonesYSelect();
    });
  } catch (ex) {
    Swal.fire({
      icon: "error",
      title: "Error de conexión",
      text: "No se pudo conectar con el servidor. Intenta nuevamente.",
    });

    document.getElementById("tableBody_pedidos_sin_producto").innerHTML = "";
  }
};

// **Función para deshabilitar o habilitar botones y select**
const toggleBotonesYSelect = () => {
  let tieneSeleccionados = productosSeleccionados.length > 0;

  $("#btnPropios, #btnBodegas, #btnPrivados, #selectBodega").prop(
    "disabled",
    tieneSeleccionados
  );
};

// **Botón para agregar productos seleccionados**
document.getElementById("btnAgregarProductos").addEventListener("click", () => {
  console.log(
    "Lista final de productos seleccionados:",
    productosSeleccionados
  );
});

// **Definir la función `actualizarBotones()`**
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

// **Eventos para cambiar entre Propios, Bodegas y Privados**
document.getElementById("btnPropios").addEventListener("click", () => {
  filtroProductos = 1;
  bodega_seleccionada = 0;
  ocultarSelectBodegas();
  actualizarBotones();
  initDataTablePedidosSinProducto();
});

document.getElementById("btnBodegas").addEventListener("click", async () => {
  filtroProductos = 2;
  bodega_seleccionada = 0;
  mostrarSelectBodegas();
  actualizarBotones();
  await cargarBodegas();
  initDataTablePedidosSinProducto();
});

document.getElementById("btnPrivados").addEventListener("click", () => {
  filtroProductos = 3;
  bodega_seleccionada = 0;
  ocultarSelectBodegas();
  actualizarBotones();
  initDataTablePedidosSinProducto();
});

// **Evento para cambiar la bodega seleccionada**
document.getElementById("selectBodega").addEventListener("change", () => {
  bodega_seleccionada = document.getElementById("selectBodega").value;
  initDataTablePedidosSinProducto();
});

// **Mostrar u ocultar el select de bodegas**
const mostrarSelectBodegas = () => {
  document.getElementById("bodegaContainer").style.display = "block";
};

const ocultarSelectBodegas = () => {
  document.getElementById("bodegaContainer").style.display = "none";
};

// **Cargar las bodegas desde la API**
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
    selectBodega.innerHTML = '<option value="0">Seleccione una bodega</option>';

    data.data.forEach((bodega) => {
      selectBodega.innerHTML += `<option value="${bodega.id_bodega}">${bodega.nombre}</option>`;
    });

    // **Inicializar Select2**
    $("#selectBodega").select2({
      width: "100%",
      placeholder: "Seleccione una bodega",
      allowClear: true,
    });
  } catch (ex) {
    Swal.fire({
      icon: "error",
      title: "Error de conexión",
      text: "No se pudo conectar con el servidor para obtener las bodegas.",
    });
  }
};

$(document).ready(function () {
  //  Hacer la función `change` `async` para poder usar `await`
  $("#selectBodega").change(async function () {
    bodega_seleccionada = $(this).val(); //  Guardar el valor seleccionado en la variable global
    await initDataTablePedidosSinProducto(); //  Recargar la tabla con la nueva bodega seleccionada
  });

  // Obtener el ID de la factura desde la URL
  let pathArray = window.location.pathname.split("/");
  let id_factura_global = pathArray[pathArray.length - 1]; // Última parte de la URL

  // Realizar el AJAX con el ID obtenido
  $.ajax({
    url:
      SERVERURL + "pedidos/obtener_factura_sin_producto/" + id_factura_global,
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status === 200 && response.data.length > 0) {
        let factura = response.data[0]; // Datos de la factura

        // Llenar la información del cliente
        $("#cliente_factura").text(factura.nombre);
        $("#telefono_factura").text(factura.telefono);
        /* $("#fecha_factura").text(new Date().toLocaleDateString()); */
        $("#total_factura").text("$" + calcularTotalFactura(factura.productos));

        // Llenar la tabla de productos
        llenarTablaProductos(factura.productos);
      } else {
        console.error("No se encontraron datos en la factura.");
      }
    },
    error: function (error) {
      console.error("Error al obtener la factura:", error);
    },
  });
});

// **Función para calcular el total de la factura**
const calcularTotalFactura = (productosString) => {
  try {
    let productos = JSON.parse(productosString); // Convertir el JSON de productos
    let total = productos.reduce(
      (acc, item) => acc + parseFloat(item.total),
      0
    );
    return total.toFixed(2); // Formato de dos decimales
  } catch (error) {
    console.error("Error al calcular total:", error);
    return "0.00";
  }
};

// **Función para llenar la tabla de productos**
const llenarTablaProductos = (productosString) => {
  try {
    let productos = JSON.parse(productosString); // Convertir el JSON de productos
    let content = "";

    productos.forEach((producto) => {
      content += `
      <tr>
        <td>${producto.nombre}</td>
        <td>${producto.cantidad}</td>
        <td>$${parseFloat(producto.precio).toFixed(2)}</td>
        <td>$${parseFloat(producto.total).toFixed(2)}</td>
      </tr>`;
    });

    $("#tableBody_productos").html(content);
  } catch (error) {
    console.error("Error al llenar la tabla de productos:", error);
  }
};

// **Inicialización al cargar la página**
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
