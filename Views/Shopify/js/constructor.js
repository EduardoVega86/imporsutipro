document.addEventListener("DOMContentLoaded", function () {
  // Event listener para el contenedor que activa la generación del enlace
  document
    .getElementById("trigger-container")
    .addEventListener("click", function () {
      document.getElementById("loading").style.display = "block";

      setTimeout(function () {
        document.getElementById("loading").style.display = "none";
        $.ajax({
          url: SERVERURL + "shopify/generarEnlace",
          type: "GET",
          dataType: "json",
          success: function (response) {
            $("#generador_enlace").val(response.url_imporsuit);
            document.getElementById("enlace-section").style.display = "block";
          },
          error: function (error) {
            console.error("Error al obtener la lista de bodegas:", error);
          },
        });
      }, 3000);
    });

  // Event listener para el botón de verificación
  document
    .getElementById("verify-button")
    .addEventListener("click", function () {
      document.getElementById("loading-below").style.display = "block";

      let intervalId = setInterval(function () {
        $.ajax({
          url: SERVERURL + "shopify/ultimoJson",
          method: "GET",
          dataType: "json",
          success: function (data) {
            if (data && data.id && data.confirmed) {
              document.getElementById("loading-below").style.display = "none";
              fillSelectsWithKeys(data);
              new bootstrap.Collapse(document.getElementById("collapseTwo"), {
                toggle: true,
              });
              clearInterval(intervalId);

              // Convertir el JSON en una cadena formateada y añadirlo al div
              document.getElementById("json-content").innerText =
                JSON.stringify(data, null, 2);

              // Mostrar el div json-informacion
              document.getElementById("json-informacion").style.display =
                "block";
            } else {
              document.getElementById("loading-below").innerHTML =
                '<div class="spinner-border" role="status"><span class="sr-only">Cargando...</span></div><div>No se pudo obtener información. Intentar nuevamente.</div>';
            }
          },
          error: function (error) {
            console.error("Error al llamar a la API:", error);
            document.getElementById("loading-below").innerHTML =
              '<div class="spinner-border" role="status"><span class="sr-only">Cargando...</span></div><div>Error al obtener información. Intentar nuevamente.</div>';
          },
        });
      }, 5000);
    });

  function fillSelectsWithKeys(data) {
    fillSelectWithKeys("select-nombre", data);
    fillSelectWithKeys("select-apellido", data);
    fillSelectWithKeys("select-principal", data);
    fillSelectWithKeys("select-secundario", data);
    fillSelectWithKeys("select-provincia", data);
    fillSelectWithKeys("select-ciudad", data);
    fillSelectWithKeys("select-codigo_postal", data);
    fillSelectWithKeys("select-pais", data);
    fillSelectWithKeys("select-telefono", data);
    fillSelectWithKeys("select-email", data);
    fillSelectWithKeys("select-total", data);
    fillSelectWithKeys("select-descuento", data);
    fillSelectWithKeys("select-referencia", data);
  }

  function fillSelectWithKeys(selectId, data) {
    const select = document.getElementById(selectId);
    if (select) {
      select.innerHTML = '<option value="" selected>-- Seleccione --</option>';
      for (let key in data) {
        if (data.hasOwnProperty(key)) {
          const option = document.createElement("option");
          option.value = key;
          option.text = key;
          select.appendChild(option);
        }
      }
      $(`#${selectId}`)
        .select2({ width: "100%" })
        .on("change", function () {
          const selectedKey = select.value;
          console.log(
            `Cambio detectado en ${selectId}, valor seleccionado: ${selectedKey}`
          );
          removeAllDynamicSelects(selectId);
          if (
            selectedKey &&
            data[selectedKey] &&
            typeof data[selectedKey] === "object"
          ) {
            createDynamicSelect(selectId, data[selectedKey]);
          }
        });
    } else {
      console.error(`El elemento con id ${selectId} no existe en el DOM.`);
    }
  }

  function createDynamicSelect(parentSelectId, nestedData) {
    const parentSelect = document.getElementById(parentSelectId);
    if (!parentSelect) return;

    const dynamicSelectId = `${parentSelectId}-dynamic-${Date.now()}`;
    let dynamicSelect = document.createElement("select");
    dynamicSelect.id = dynamicSelectId;
    dynamicSelect.className = "form-select mt-2";
    parentSelect.parentNode.appendChild(dynamicSelect);

    dynamicSelect.innerHTML =
      '<option value="" selected>-- Seleccione --</option>';
    for (let key in nestedData) {
      if (nestedData.hasOwnProperty(key)) {
        const option = document.createElement("option");
        option.value = key;
        option.text = key;
        dynamicSelect.appendChild(option);
      }
    }

    console.log(
      `Opciones dinámicas creadas para ${parentSelectId}:`,
      dynamicSelect.innerHTML
    );

    $(`#${dynamicSelectId}`)
      .select2({ width: "100%" })
      .on("change", function () {
        const selectedKey = dynamicSelect.value;
        console.log(
          `Cambio detectado en ${dynamicSelectId}, valor seleccionado: ${selectedKey}`
        );
        removeAllDynamicSelects(dynamicSelectId);
        if (
          selectedKey &&
          nestedData[selectedKey] &&
          typeof nestedData[selectedKey] === "object"
        ) {
          createDynamicSelect(dynamicSelectId, nestedData[selectedKey]);
        }
      });
  }

  function removeAllDynamicSelects(parentSelectId) {
    console.log(
      `Eliminando todos los selects dinámicos relacionados con ${parentSelectId}`
    );
    const parentSelect = document.getElementById(parentSelectId);
    if (!parentSelect) return;

    // Remover todos los selects dinámicos dentro del mismo contenedor
    const dynamicSelects = parentSelect.parentNode.querySelectorAll("select");
    dynamicSelects.forEach((select) => {
      if (
        select.id !== parentSelectId &&
        select.id.startsWith(parentSelectId)
      ) {
        console.log(`Eliminando select dinámico: ${select.id}`);
        $(`#${select.id}`).select2("destroy"); // Destruir Select2 antes de eliminar
        select.parentNode.removeChild(select);
      }
    });
  }

  document.getElementById("send-button").addEventListener("click", function () {
    const formData = new FormData();

    // List of principal select IDs
    const selectIds = [
      "select-nombre",
      "select-apellido",
      "select-principal",
      "select-secundario",
      "select-provincia",
      "select-ciudad",
      "select-codigo_postal",
      "select-pais",
      "select-telefono",
      "select-email",
      "select-total",
      "select-descuento",
      "select-referencia",
    ];

    selectIds.forEach((selectId) => {
      const values = getSelectValues(selectId);
      if (values.length > 0) {
        formData.append(selectId.split("-")[1], values.join("/"));
      }
    });

    $.ajax({
      url: SERVERURL + "shopify/guardarConfiguracion",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        response = JSON.parse(response);
        if (response.status == 500) {
          Swal.fire({
            icon: "error",
            title: "Error al guardar",
            text: response.message,
          });
        } else if (response.status == 200) {
          Swal.fire({
            icon: "success",
            title: "Guardado Correctamente",
            text: response.message,
            showConfirmButton: false,
            timer: 2000,
          }).then(() => {
            window.location.href = "" + SERVERURL + "shopify/constructor_vista";
          });
        }
      },
      error: function (error) {
        console.error("Error al enviar los datos:", error);
      },
    });
  });

  function getSelectValues(selectId) {
    const values = [];
    let currentSelectId = selectId;

    while (document.getElementById(currentSelectId)) {
      const select = document.getElementById(currentSelectId);
      if (select && select.value) {
        values.push(select.value);
      }
      // Modificar la lógica para obtener correctamente los selects dinámicos
      const dynamicSelects = document.querySelectorAll(
        `select[id^="${currentSelectId}-dynamic"]`
      );
      dynamicSelects.forEach((dynamicSelect) => {
        if (dynamicSelect && dynamicSelect.value) {
          values.push(dynamicSelect.value);
        }
      });
      break; // Romper el bucle después de procesar el primer select principal y sus dinámicos
    }

    return values;
  }

  // Escuchar cambios en cualquier select del documento
  document.addEventListener("change", function (event) {
    if (event.target && event.target.nodeName === "SELECT") {
      console.log(`Cambio detectado en select con id ${event.target.id}`);
    }
  });
});

let dataTableProductosShopify;
let dataTableProductosShopifyIsInitialized = false;

const dataTableProductosShopifyOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
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

const initDataTableProductosShopify = async () => {
  if (dataTableProductosShopifyIsInitialized) {
    dataTableProductosShopify.destroy();
  }

  await listProductosShopify();

  dataTableProductosShopify = $("#datatable_productos_shopify").DataTable(
    dataTableProductosShopifyOptions
  );

  dataTableProductosShopifyIsInitialized = true;
};

const listProductosShopify = async () => {
  try {
    const response = await fetch("" + SERVERURL + "Productos/obtener_productos_shopify");
    const productosShopify = await response.json();

    let content = ``;

    productosShopify.forEach((producto, index) => {
      content += `
                <tr>
                    <td><a class="dropdown-item link-like" href="${SERVERURL}shopify/verProducto?producto=${producto.id}">${producto.nombre}</a></td>
                    <td>${producto.precio}</td>
                    <td>${producto.stock}</td>
                    <td>${producto.categoria}</td>
                    <td>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel_general('${producto.id}')">Descargar Excel general</button>
                    <button id="downloadExcel" class="btn btn-success" onclick="descargarExcel('${producto.id}')">Descargar Excel</button>
                    </td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" style="cursor: pointer;" href="${SERVERURL}shopify/editarProducto?producto=${producto.id}"><i class='bx bx-edit'></i>Editar</a></li>
                        <li><a class="dropdown-item" style="cursor: pointer;" href="${SERVERURL}shopify/eliminarProducto?producto=${producto.id}"><i class='bx bx-trash'></i>Eliminar</a></li>
                    </ul>
                    </div>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_productos_shopify").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableProductosShopify();
});
