let dataTableNuevosPedidos;
let dataTableNuevosPedidosIsInitialized = false;

// Obtener el valor del id_factura desde la URL
var url_1 = window.location.href;
var id_factura_1 = url_1.split("/").pop();
const getFecha = () => {
  let fecha = new Date();
  let dia = fecha.getDate();
  let mes = fecha.getMonth() + 1;
  let anio = fecha.getFullYear();
  let hora = fecha.getHours();
  let minutos = fecha.getMinutes();
  let segundos = fecha.getSeconds();
  return `${dia}-${mes}-${anio}_${hora}-${minutos}-${segundos}`;
};

const dataTableNuevosPedidosOptions = {
  destroy: true,
  responsive: true,
  buttons: [
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel"></i>',
      title: "Panel de Control: Usuarios",
      titleAttr: "Exportar a Excel",
      exportOptions: {
        columns: [1, 2, 3, 4, 5],
      },
      filename: "Productos" + "_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      title: "Panel de Control: Productos",
      titleAttr: "Exportar a CSV",
      exportOptions: {
        columns: [1, 2, 3, 4, 5],
      },
      filename: "Productos" + "_" + getFecha(),
      footer: true,
      className: "btn-csv",
    },
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
      previous: "Anterior",
    },
  },
};

const initDataTableNuevosPedidos = async () => {
  if (dataTableNuevosPedidosIsInitialized) {
    dataTableNuevosPedidos.destroy();
  }

  // Esperar la carga de los datos antes de inicializar la tabla
  await listNuevosPedidos();

  dataTableNuevosPedidos = $("#datatable_nuevosPedidos").DataTable(
    dataTableNuevosPedidosOptions
  );

  dataTableNuevosPedidosIsInitialized = true;
};

const listNuevosPedidos = async () => {
  var id_producto = $("#id_productoBuscar_0").val();
  var sku = $("#sku_productoBuscar_0").val();
  // Crear una instancia de FormData
  let formData = new FormData();
  formData.append("sku", sku); // Añadir el SKU al FormData
  //fetch
  const response = await fetch(
    `${SERVERURL}pedidos/buscarProductosBodega/${id_producto}`,
    {
      method: "POST",
      body: formData,
    }
  );
  const productos = await response.json();
  console.log(productos);

  if (productos) {
    let content = "";
    productos.forEach((nuevoPedido, index) => {
      let imagen = obtenerURLImagen(nuevoPedido.image_path, SERVERURL);
      content += `
                        <tr>
                            <td><img src="${imagen}" class="icon-button" width="50px"></td>
                            <td>${nuevoPedido.id_producto}</td>
                            <td>${nuevoPedido.nombre_producto}</td>
                            <td>${nuevoPedido.stock_inicial}</td>
                            <td><input type="number" class="form-control" value="1" min="1" id="cantidad_${index}"></td>
                            <td>${nuevoPedido.pvp}</td>
                            <td>
                            <button class="btn btn-sm btn-success" onclick="enviar_cliente(${nuevoPedido.id_producto}, ${index})"><i class="fa-solid fa-plus"></i></button>
                            </td>
                        </tr>`;
    });
    document.getElementById("tableBody_nuevosPedidos").innerHTML = content;
  } else {
    console.error("La respuesta no es un array:", nuevosPedidos);
    alert("Error: La respuesta no tiene el formato esperado.");
  }
};

function obtenerURLImagen(imagePath, serverURL) {
  // Verificar si el imagePath no es null o undefined
  if (imagePath) {
    // Verificar si el imagePath ya es una URL completa
    if (imagePath.startsWith("http://") || imagePath.startsWith("https://")) {
      // Si ya es una URL completa, retornar solo el imagePath
      return imagePath;
    } else {
      // Verificar si el imagePath incluye rutas relativas inválidas
      if (
        imagePath.includes("../") ||
        imagePath.includes("..\\") ||
        imagePath === "" ||
        imagePath === "."
      ) {
        return serverURL + "public/img/broken-image.png"; // Ruta de imagen por defecto
      }
      // Si no es una URL completa, agregar el serverURL al inicio
      return `${serverURL}${imagePath}`;
    }
  } else {
    // Manejar el caso cuando imagePath es null o undefined
    console.error("imagePath es null o undefined");
    return serverURL + "public/img/broken-image.png"; // Ruta de imagen por defecto
  }
}

// Abrir modal
function buscar_productos_nuevoPedido() {
  $("#nuevosPedidosModal").modal("show");
}

//enviar cliente
//enviar cliente
function enviar_cliente(id, index) {
  // Obtener el valor del input cantidad correspondiente
  let cantidad = $(`#cantidad_${index}`).val();

  $.ajax({
    type: "POST",
    url: SERVERURL + "marketplace/obtener_producto/" + id,
    dataType: "json",
    success: function (response) {
      if (response) {
        const data = response[0];

        // Crear un objeto FormData y agregar los datos
        const formData = new FormData();
        formData.append("cantidad", cantidad); // Utilizar la cantidad obtenida
        formData.append("precio", data.pvp);
        formData.append("id_producto", data.id_producto);
        formData.append("id_inventario", data.id_inventario);
        formData.append("sku", data.sku);
        formData.append("id_factura", id_factura_1);

        $.ajax({
          type: "POST",
          url: SERVERURL + "pedidos/agregarDetalle",
          data: formData,
          processData: false,
          contentType: false,
          success: function (response2) {
            response2 = JSON.parse(response2);
            console.log(response2);
            console.log(response2[0]);
            if (response2.status == 500) {
              Swal.fire({
                icon: "error",
                title: response2.title,
                text: response2.message,
              });
            } else if (response2.status == 200) {
              console.log("entro en el 200");
              initDataTableNuevosPedidos();
            }
          },
          error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX:", error);
            alert("Hubo un problema al agregar detalle");
          },
        });
      } else {
        console.error("La respuesta está vacía o tiene un formato incorrecto.");
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al obtener la información del producto");
    },
  });
}
