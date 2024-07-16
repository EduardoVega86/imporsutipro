let dataTableBanner;
let dataTableBannerIsInitialized = false;

let dataTableCaracteristicas;
let dataTableCaracteristicasIsInitialized = false;

const dataTableBannerOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
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
      previous: "Anterior",
    },
  },
};

const dataTableCaracteristicasOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
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
      previous: "Anterior",
    },
  },
};

const initDataTableBanner = async () => {
  if (dataTableBannerIsInitialized) {
    dataTableBanner.destroy();
  }

  await listBanner();

  dataTableBanner = $("#datatable_banner").DataTable(dataTableBannerOptions);

  dataTableBannerIsInitialized = true;
};

const initDataTableCaracteristicas = async () => {
  if (dataTableCaracteristicasIsInitialized) {
    dataTableCaracteristicas.destroy();
  }

  await listCaracteristicas();

  dataTableCaracteristicas = $("#datatable_caracteristicas").DataTable(
    dataTableCaracteristicasOptions
  );

  dataTableBannerIsInitialized = true;
};

const listBanner = async () => {
  try {
    const response = await fetch(
      "" + SERVERURL + "Usuarios/obtener_bannertienda"
    );
    const banner = await response.json();

    let content = ``;
    let alineacion = "";
    banner.forEach((item, index) => {
      if (item.alineacion == 1) {
        alineacion = "izquierda";
      } else if (item.alineacion == 2) {
        alineacion = "centro";
      } else if (item.alineacion == 3) {
        alineacion = "derecha";
      }
      content += `
                <tr>
                    <td><img src="${SERVERURL}${item.fondo_banner}" class="img-responsive" alt="profile-image" width="100px"></td>
                    <td>${item.titulo}</td>
                    <td>${item.texto_banner}</td>
                    <td>${item.texto_boton}</td>
                    <td>${item.enlace_boton}</td>
                    <td>${alineacion}</td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="editar_banner(${item.id})"><i class="fa-solid fa-pencil"></i>Editar</span></li>
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="eliminarBanner(${item.id})"><i class="fa-solid fa-trash-can"></i>Eliminar</span></li>
                    </ul>
                    </div>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_banner").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

const listCaracteristicas = async () => {
  try {
    const response = await fetch(
      "" + SERVERURL + "Usuarios/obtener_caracteristica"
    );
    const banner = await response.json();

    let content = ``;
    let alineacion = "";
    banner.forEach((item, index) => {
      if (item.alineacion == 1) {
        alineacion = "izquierda";
      } else if (item.alineacion == 2) {
        alineacion = "centro";
      } else if (item.alineacion == 3) {
        alineacion = "derecha";
      }
      content += `
                <tr>
                    
                    <td>${item.texto}</td>
                    <td>${item.icon_text}</td>
                    <td>${item.subtexto_icon}</td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="editar_caracteristica(${item.id})"><i class="fa-solid fa-pencil"></i>Editar</span></li>
                        
                    </ul>
                    </div>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_caracteristicas").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

function editar_banner(id) {
  let formData = new FormData();
  formData.append("id", id);
 
 
 
  $.ajax({
    url: SERVERURL + "Usuarios/obtener_bannertiendaID",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      console.log("opc 1:" + response.id);
      console.log("opc 2:" + response[0].id);
      $("#id_banner").val(response[0].id);
      $("#titulo_editar").val(response[0].titulo);
      $("#texto_banner_editar").val(response[0].texto_banner);
      $("#texto_boton_editar").val(response[0].texto_boton);
      $("#enlace_boton_editar").val(response[0].enlace_boton);
      $("#alineacion_editar").val(response[0].alineacion).change();
      $("#preview-imagen-editar")
        .attr("src", SERVERURL + response[0].fondo_banner)
        .show();
      $("#editar_bannerModal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

function eliminarBanner(id) {
  let formData = new FormData();
  formData.append("id", id);

  $.ajax({
    type: "POST",
    url: SERVERURL + "Usuarios/eliminarBanner",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      response = JSON.parse(response);
      // Mostrar alerta de éxito
      if (response.status == 500) {
        toastr.error("NO SE ELIMINO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else {
        toastr.success("SE ELIMINO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        initDataTableBanner();
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al eliminar la categoría");
    },
  });
}

window.addEventListener("load", async () => {
  await initDataTableBanner();
  await initDataTableCaracteristicas();
});

function crear_tienda() {
  var nombre_tienda = $("#nombre_tienda").val();

  let formData = new FormData();
  formData.append("nombre", nombre_tienda); // Añadir el SKU al FormData

  $.ajax({
    url: SERVERURL + "Usuarios/registro",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {},
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

document.addEventListener("DOMContentLoaded", function () {
  const input = document.getElementById("nombre_tienda");
  input.addEventListener("input", function () {
    validateStoreName(function (isValid) {
      if (!isValid) {
        // Handle invalid case if needed
      }
    });
  });
});

function validateStoreName(callback) {
  const input = document.getElementById("nombre_tienda");
  const label = document.querySelector('label[for="nombre_tienda"]');
  const errorDiv = document.getElementById("tienda-error");
  const regex = /^[a-zA-Z]*$/;

  input.value = input.value.toLowerCase();

  if (!regex.test(input.value)) {
    label.classList.remove("text-green-500");
    label.classList.add("text-red-500", "border-red-500");
    errorDiv.textContent =
      "El nombre de la tienda no puede contener espacios ni caracteres especiales como (/, ^, *, $, @, \\)";
    errorDiv.style.display = "block";
    input.value = input.value.slice(0, -1);
    callback(false);
    return;
  }

  fetch(SERVERURL + "Acceso/validar_tiendas", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      tienda: input.value,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.exists) {
        errorDiv.textContent = "Esta tienda ya existe.";
        errorDiv.style.display = "block";
        callback(false);
        $("#seccion_creacionTienda").hide();
      } else {
        errorDiv.style.display = "none";
        callback(true);
        $("#seccion_creacionTienda").show();
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      callback(false);
    });
}

function crear_tienda() {
  var nombre_tienda = $("#nombre_tienda").val();

  let formData = new FormData();
  formData.append("nombre", nombre_tienda); // Añadir el SKU al FormData

  $.ajax({
    url: SERVERURL + "Usuarios/registro",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {},
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

/* tabla de Testimonio */
let dataTableTestimonios;
let dataTableTestimoniosIsInitialized = false;

const dataTableTestimoniosOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4] },
    { orderable: false, targets: 0 }, //ocultar para columna 0 el ordenar columna
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
      previous: "Anterior",
    },
  },
};

const initDataTableTestimonios = async () => {
  if (dataTableTestimoniosIsInitialized) {
    dataTableTestimonios.destroy();
  }

  await listTestimonios();

  dataTableTestimonios = $("#datatable_testimonios").DataTable(
    dataTableTestimoniosOptions
  );

  dataTableTestimoniosIsInitialized = true;
};

const listTestimonios = async () => {
  try {
    const response = await fetch(
      "" + SERVERURL + "Usuarios/obtener_testimonios"
    );
    const testimonios = await response.json();

    let content = ``;

    testimonios.forEach((testimonio, index) => {
      content += `
                <tr>
                    <td><img src="${SERVERURL}${testimonio.imagen}" class="img-responsive" alt="profile-image" width="100px"></td>
                    <td>${testimonio.nombre}</td>
                    <td>${testimonio.testimonio}</td>
                    <td>${testimonio.date_added}</td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="editarTestimonio(${testimonio.id_testimonio})"><i class="fa-solid fa-pencil"></i>Editar</span></li>
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="eliminarTestimonio(${testimonio.id_testimonio})"><i class="fa-solid fa-trash-can"></i>Eliminar</span></li>
                    </ul>
                    </div>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_testimonios").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableTestimonios();
});

function eliminarTestimonio(id) {
  let formData = new FormData();
  formData.append("id", id);

  $.ajax({
    type: "POST",
    url: SERVERURL + "Usuarios/eliminarTestimonio",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    success: function (response) {
      response = JSON.parse(response);
      // Mostrar alerta de éxito
      if (response.status == 500) {
        toastr.error("NO SE ELIMINO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else {
        toastr.success("SE ELIMINO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        initDataTableTestimonios();
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al eliminar la categoría");
    },
  });
}
/* Fin tabla de testimonios */

/* boton flotante de actualizar */
document.addEventListener("DOMContentLoaded", () => {
  const botonFlotante = document.getElementById("botonFlotante");
  const inputs = document.querySelectorAll(
    "input.cambio, textarea.cambio, select.cambio"
  );

  if (!botonFlotante) {
    console.error("El botón flotante no se encontró en el DOM");
    return;
  }

  let cambiosRealizados = false;

  inputs.forEach((input) => {
    input.addEventListener("input", () => {
      cambiosRealizados = true;
      mostrarBoton();
    });
  });

  function mostrarBoton() {
    if (cambiosRealizados) {
      botonFlotante.classList.add("mostrar");
    }
  }

  botonFlotante.addEventListener("click", () => {
    // Lógica para guardar cambios
    alert("Cambios guardados");
    cambiosRealizados = false;
    botonFlotante.classList.remove("mostrar");
  });
});

/* Fin boton flotante de actualizar */
