// Inicializa la tabla inventario variable
let dataTableInventario;
let dataTableInventarioIsInitialized = false;

const dataTableInventarioOptions = {
  paging: false,
  searching: false,
  info: false,
  lengthChange: false,
  destroy: true,
  autoWidth: false,
  language: {
    emptyTable: "No hay datos disponibles en la tabla",
    loadingRecords: "Cargando...",
  },
};

const initDataTableInventario = async () => {
  if (dataTableInventarioIsInitialized) {
    dataTableInventario.destroy();
  }

  await listAtributosInventario();

  dataTableInventario = $("#datatable_inventarioVariable").DataTable(
    dataTableInventarioOptions
  );

  dataTableInventarioIsInitialized = true;
};

const listAtributosInventario = async () => {
  try {
    const response = await fetch(`${SERVERURL}productos/listar_atributos`);
    const atributos = await response.json();
    const caracteristicas = await listarCaracteristicasInventario();

    let content = ``;
    atributos.forEach((atributo, index) => {
      const tags = caracteristicas
        .filter(
          (caracteristica) =>
            caracteristica.id_atributo === atributo.id_atributo
        )
        .map(
          (caracteristica) => `
                    <span class="tag" data-atributo-id="${atributo.id_atributo}" data-valor="${caracteristica.variedad}" data-variedad-id="${caracteristica.id_variedad}">
                        ${caracteristica.variedad} <span class="remove-tag" data-atributo-id="${atributo.id_atributo}" data-valor="${caracteristica.variedad}" data-variedad-id="${caracteristica.id_variedad}">&times;</span>
                    </span>`
        )
        .join("");

      content += `
                <tr>
                    <td>${atributo.nombre_atributo}</td>
                    <td>${tags}</td>
                    <td><input id="agregar_atributo_${index}" name="agregar_atributo" class="form-control agregar_atributo" type="text" data-atributo-id="${atributo.id_atributo}"></td>
                </tr>`;
    });

    document.getElementById("tableBody_inventarioVariable").innerHTML = content;

    // Agregar event listeners a todos los inputs recién creados
    document.querySelectorAll(".agregar_atributo").forEach((input) => {
      input.addEventListener("keypress", async (event) => {
        if (event.key === "Enter") {
          event.preventDefault(); // Previene el comportamiento por defecto del Enter
          const atributoId = event.target.getAttribute("data-atributo-id");
          const valor = event.target.value;

          if (valor.trim() !== "") {
            await agregarCaracteristicaInventario(atributoId, valor);
            event.target.value = ""; // Clear the input after submission
            await listAtributosInventario(); // Refresh the list of attributes
          }
        }
      });
    });

    // Agregar event listeners a todos los botones de eliminar etiqueta
    document.querySelectorAll(".remove-tag").forEach((span) => {
      span.addEventListener("click", async (event) => {
        const variedadoId = event.target.getAttribute("data-variedad-id");
        await eliminarCaracteristicaInventario(variedadoId);
        await listAtributosInventario(); // Refresh the list of attributes
      });
    });

    // Agregar event listeners a todos los tags, excepto la X
    document.querySelectorAll(".tag").forEach((tag) => {
      tag.addEventListener("click", (event) => {
        if (!event.target.classList.contains("remove-tag")) {
          const atributoId = tag.getAttribute("data-atributo-id");
          const id_variedad = tag.getAttribute("data-variedad-id");
          const valor = tag.getAttribute("data-valor");
          const id_productoVariable = $("#id_productoVariable").val();
          $.ajax({
            url: SERVERURL + "Productos/consultarMaximo/" + id_productoVariable,
            type: "GET",
            dataType: "text",
            success: function (response) {
              document.getElementById("valor_guardar").value = valor;
              document.getElementById("id_variedadTemporadal").value =
                id_variedad;
              document.getElementById("sku_guardar").value = response;
            },
            error: function (error) {
              console.error("Error al obtener la lista de bodegas:", error);
            },
          });
        }
      });
    });
  } catch (ex) {
    alert("Error al cargar los atributos: " + ex.message);
  }
};

const listarCaracteristicasInventario = async () => {
  try {
    const response = await fetch(
      `${SERVERURL}productos/listar_caracteristicas`
    );
    if (response.ok) {
      const data = await response.json();
      return data;
    } else {
      throw new Error("Error al listar las características");
    }
  } catch (ex) {
    alert("Error al conectarse a la API: " + ex.message);
    return [];
  }
};

const agregarCaracteristicaInventario = async (atributoId, valor) => {
  try {
    const formData = new FormData();
    formData.append("id_atributo", atributoId);
    formData.append("variedad", valor);

    const response = await fetch(
      `${SERVERURL}productos/agregar_caracteristica`,
      {
        method: "POST",
        body: formData,
      }
    );

    if (response.ok) {
      alert("Característica agregada exitosamente");
    } else {
      const error = await response.json();
      alert("Error al agregar la característica: " + error.message);
    }
  } catch (ex) {
    alert("Error al conectarse a la API: " + ex.message);
  }
};

const eliminarCaracteristicaInventario = async (variedadoId) => {
  try {
    const formData = new FormData();
    formData.append("id", variedadoId);

    const response = await fetch(
      `${SERVERURL}productos/eliminar_caracteristica`,
      {
        method: "POST",
        body: formData,
      }
    );

    if (response.ok) {
      alert("Característica eliminada exitosamente");
    } else {
      const error = await response.json();
      alert("Error al eliminar la característica: " + error.message);
    }
  } catch (ex) {
    alert("Error al conectarse a la API: " + ex.message);
  }
};

//cargar select de bodega
$(document).ready(function () {
  // Realiza la solicitud AJAX para obtener la lista de bodegas
  $.ajax({
    url: SERVERURL + "productos/listar_bodegas",
    type: "GET",
    dataType: "json",
    success: function (response) {
      // Asegúrate de que la respuesta es un array
      if (Array.isArray(response)) {
        response.forEach(function (bodega) {
          // Agrega una nueva opción al select por cada bodega
          $("#bodega_inventarioVariable").append(
            new Option(bodega.nombre, bodega.id)
          );
        });
      } else {
        console.log("La respuesta de la API no es un array:", response);
      }
    },
    error: function (error) {
      console.error("Error al obtener la lista de bodegas:", error);
    },
  });
});

// Agregar variedad
function agregar_variedad() {
  let formData = new FormData();
  formData.append("id_variedad", $("#id_variedadTemporadal").val());
  formData.append("id_producto", $("#id_productoVariable").val());
  formData.append("sku", $("#sku_guardar").val());
  formData.append("id_bodega", $("#bodega_inventarioVariable").val());
  formData.append("pcp", $("#precioProveedor_guardar").val());
  formData.append("pvp", $("#precioVenta_guardar").val());
  formData.append("pref", $("#precioRefe_guardar").val());
  formData.append("stock", $("#stockInicial_guardar").val());

  $.ajax({
    url: SERVERURL + "Productos/agregarVariable",
    type: "POST", // Cambiar a POST para enviar FormData
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      initDataTableDetalleInventario();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

window.addEventListener("load", async () => {
  await initDataTableInventario();
});

// tabla detalle inventario
let dataTableDetalleInventario;
let dataTableDetalleInventarioIsInitialized = false;

const dataTableDetalleInventarioOptions = {
  columnDefs: [{ className: "centered", targets: [0, 1, 2, 3, 4, 5, 6] }],
  pageLength: 10,
  destroy: true,
  language: {
    lengthMenu: "Mostrar _MENU_ registros por página",
    zeroRecords: "Ningún detalle de inventario encontrado",
    info: "Mostrando de _START_ a _END_ de un total de _TOTAL_ registros",
    infoEmpty: "Ningún detalle de inventario encontrado",
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

const initDataTableDetalleInventario = async () => {
  if (dataTableDetalleInventarioIsInitialized) {
    dataTableDetalleInventario.destroy();
  }

  await listDetalleInventario();

  dataTableDetalleInventario = $("#datatable_detalleInventario").DataTable(
    dataTableDetalleInventarioOptions
  );

  dataTableDetalleInventarioIsInitialized = true;
};

const listDetalleInventario = async () => {
  const id_productoVariable = $("#id_productoVariable").val();
  try {
    const response = await fetch(
      "" + SERVERURL + "productos/mostrarVariedades/" + id_productoVariable
    );
    const detalleInventario = await response.json();
    //nombre es nombre_bodega
    let content = ``;
    detalleInventario.forEach((detalle, index) => {
      content += `
      <tr>
      <td>${detalle.variedad}</td>
      <td>${detalle.sku}</td>
      <td>${detalle.pcp}</td>
      <td>${detalle.pvp}</td>
      <td>${detalle.pref}</td>
      <td>${detalle.nombre}</td>
      <td>${detalle.stock_inicial}</td>
        </tr>`;
    });
    document.getElementById("tableBody_detalleInventario").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};
