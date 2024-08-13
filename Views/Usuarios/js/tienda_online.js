$("#imageInputPrincipal").on("change", function (event) {
  event.preventDefault();

  // Mostrar vista previa de la imagen seleccionada
  var input = event.target;
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $("#imagen_logo").attr("src", e.target.result);
    };
    reader.readAsDataURL(input.files[0]);
  }

  // Crear un FormData y enviar la imagen mediante AJAX
  var formData = new FormData($("#imageFormPrincipal")[0]);
  $.ajax({
    url: SERVERURL + "Usuarios/guardar_imagen_logo", // Cambia esta ruta por la ruta correcta a tu controlador
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      response = JSON.parse(response);
      if (response.status == 500) {
        toastr.error("LA IMAGEN NO SE AGREGRO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("IMAGEN AGREGADA CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert("Error al guardar la imagen: " + textStatus);
    },
  });
});

$("#imageInputFav").on("change", function (event) {
  event.preventDefault();

  // Mostrar vista previa de la imagen seleccionada
  var input = event.target;
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $("#imagePreviewFav").attr("src", e.target.result);
    };
    reader.readAsDataURL(input.files[0]);
  }

  // Crear un FormData y enviar la imagen mediante AJAX
  var formData = new FormData($("#imageFormFavicon")[0]);
  $.ajax({
    url: SERVERURL + "Usuarios/guardar_imagen_favicon", // Cambia esta ruta por la ruta correcta a tu controlador
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      response = JSON.parse(response);
      if (response.status == 500) {
        toastr.error("LA IMAGEN NO SE AGREGÓ CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("IMAGEN AGREGADA CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert("Error al guardar la imagen: " + textStatus);
    },
  });
});

$(document).ready(function () {
  cargarInfoTienda_inicial();

  // Cargar el archivo JSON
  $.getJSON(SERVERURL + "Views/Usuarios/json/iconos.json", function (data) {
    // Iterar sobre los datos y agregar opciones al select
    $.each(data, function (index, item) {
      $("#icono").append(
        $("<option>", {
          value: item["icon-name"],
          text: item["icon-name"],
        })
      );
    });

    // Inicializar Select2 dentro del modal
    $("#icono").select2({
      dropdownParent: $("#editar_iconoModal"), // Asigna el dropdown al modal
      templateResult: formatIcon, // Formato para la lista de resultados
      templateSelection: formatIcon, // Formato para el elemento seleccionado
    });
  });

  // Función para formatear las opciones con icono y texto
  function formatIcon(icon) {
    if (!icon.id) {
      return icon.text;
    }
    var $icon = $(
      '<span><i class="fa ' + icon.id + '"></i> ' + icon.text + "</span>"
    );
    return $icon;
  }
});

function cargar_ofertas_plantilla2() {
  $.ajax({
    url: SERVERURL + "Usuarios/obtener_ofertas_plantilla2",
    type: "GET",
    dataType: "json",
    success: function (response) {
      $("#titulo_oferta1").val(response[0].titulo_oferta1);
      $("#oferta1").val(response[0].oferta1);
      $("#descripcion_oferta1").val(response[0].descripcion_oferta1);
      $("#textoBtn_oferta1").val(response[0].textoBtn_oferta1);
      $("#enlace_oferta1").val(response[0].enlace_oferta1);
      $("#color_btn_oferta1").val(response[0].color_btn_oferta1);

      if (response[0].imagen_oferta1 === null) {
        $("#imagen_oferta1").attr(
          "src",
          SERVERURL + "public/img/broken-image.png"
        );
      } else {
        $("#imagen_oferta1").attr("src", SERVERURL + response[0].imagen_oferta1);
      }

      $("#titulo_oferta2").val(response[0].titulo_oferta2);
      $("#oferta2").val(response[0].oferta2);
      $("#descripcion_oferta2").val(response[0].descripcion_oferta2);
      $("#textoBtn_oferta2").val(response[0].textoBtn_oferta2);
      $("#enlace_oferta2").val(response[0].enlace_oferta2);
      $("#color_btn_oferta2").val(response[0].color_btn_oferta2);

      if (response[0].imagen_oferta2 === null) {
        $("#imagen_oferta2").attr(
          "src",
          SERVERURL + "public/img/broken-image.png"
        );
      } else {
        $("#imagen_oferta2").attr("src", SERVERURL + response[0].imagen_oferta2);
      }
    },
    error: function (error) {
      console.error(
        "Error al obtener la informacion ofertas plantilla 2:",
        error
      );
    },
  });
}

function cargarInfoTienda_inicial() {
  $.ajax({
    url: SERVERURL + "Usuarios/obtener_infoTiendaOnline",
    type: "GET",
    dataType: "json",
    success: function (response) {
      $("#nombre_tienda").val(response[0].nombre_tienda);
      // Actualiza el atributo 'value' del input con el mismo valor
      $("#nombre_tienda").attr("value", response[0].nombre_tienda);

      $("#texto_cabecera").val(response[0].texto_cabecera);
      $("#texto_footer").val(response[0].texto_footer);
      $("#texto_precio").val(response[0].texto_precio);
      $("#color").val(response[0].color);
      $("#color_botones").val(response[0].color_botones);
      $("#texto_boton1").val(response[0].texto_boton);
      $("#ruc").val(response[0].cedula_facturacion);

      if (response[0].tienda_creada == 1) {
        $("#nombre_tienda").prop("readonly", true);
        $("#tienda-creada").html(
          '<a href="' +
            response[0].url_imporsuit +
            '" target="_blank">Ver mi tienda</a>'
        );
        $("#crear_tienda").css("display", "none");
        $("#seccion_nosePermiteTMP").hide();
      }

      $("#whatsapp").val(response[0].whatsapp);
      $("#email").val(response[0].email);
      $("#direccion_tienda").val(response[0].direccion_facturacion);
      $("#imagen_logo").attr("src", SERVERURL + response[0].logo_url);
      $("#imagePreviewFav").attr("src", SERVERURL + response[0].favicon);

      $("#instagram").val(response[0].instagram);
      $("#tiktok").val(response[0].tiktok);
      $("#facebook").val(response[0].facebook);

      $("#dominio").val(response[0].dominio);

      var subdominio = quitarHTTPS(response[0].url_imporsuit);

      $("#subdominio").val(subdominio);

      if (response[0].plantilla == 1) {
        $("#colores_plantilla1").show();
        $("#colores_plantilla2").hide();
        $("#seccion_oferta_plantilla2").hide();
      } else if (response[0].plantilla == 2) {
        cargar_ofertas_plantilla2();
        $("#colores_plantilla2").show();
        $("#seccion_oferta_plantilla2").show();
        $("#colores_plantilla1").hide();
      }

      // Mover la lógica de verificación aquí
      verificarNombreTienda(response[0].nombre_tienda);

      if (response[0].tienda_creada == 1) {
        $("#seccion_creacionTienda").hide();
      } else if (response[0].tienda_creada == 0) {
        $("#seccion_creacionTienda").show();
      }
    },
    error: function (error) {
      console.error("Error al obtener la lista de bodegas:", error);
    },
  });
}

function quitarHTTPS(url) {
  // Verifica si la URL es null o vacía
  if (!url) {
    return ""; // Retorna una cadena vacía si la URL es null o vacía
  }

  return url.replace("https://", ""); // Reemplaza 'https://' por una cadena vacía
}

function verificarNombreTienda(nombreTienda) {
  if (nombreTienda.includes("TMP_") || nombreTienda.includes("tmp_")) {
    $("#seccion_nosePermiteTMP").show();
    $("#seccion_creacionTienda").hide();
  } else {
    $("#seccion_nosePermiteTMP").hide();
    $("#seccion_creacionTienda").show();
  }
}

function cambiarcolor(campo, valor) {
  const formData = new FormData();
  formData.append("campo", campo);
  formData.append("valor", valor);

  $.ajax({
    type: "POST",
    url: "" + SERVERURL + "Usuarios/cambiarcolor",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response2) {
      response2 = JSON.parse(response2);
      console.log(response2);
      console.log(response2[0]);
      if (response2.status == 200) {
        Swal.fire({
          icon: "error",
          title: "Exito",
          text: "Color cambiado correctamente",
        });
      } else if (response2.status == 200) {
        Swal.fire({
          icon: "error",
          title: response2.title,
          text: response2.message,
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al agregar el producto temporalmente");
    },
  });
}

let dataTableBanner;
let dataTableBannerIsInitialized = false;

let dataTableCaracteristicas;
let dataTableCaracteristicasIsInitialized = false;

const dataTableBannerOptions = {
  columnDefs: [
    {
      className: "centered",
      targets: [1, 2, 3, 4, 5],
    },
    {
      orderable: false,
      targets: 0,
    }, //ocultar para columna 0 el ordenar columna
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
    {
      className: "centered",
      targets: [1, 2, 3],
    },
    {
      orderable: false,
      targets: 0,
    }, //ocultar para columna 0 el ordenar columna
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

  dataTableCaracteristicasIsInitialized = true; // Corrige el nombre de la variable aquí
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
    const caracteristicas = await response.json();

    let content = ``;
    let alineacion = "";
    caracteristicas.forEach((item, index) => {
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
              <td><i class="fa ${item.icon_text} fa-2x"></i></td>
              <td>${item.subtexto_icon}</td>
              <td>${item.enlace_icon}</td>
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

function editar_caracteristica(id) {
  let formData = new FormData();
  formData.append("id", id); // Añadir el SKU al FormData

  $.ajax({
    url: SERVERURL + "Usuarios/obtener_caracteristica_id",
    type: "POST", // Cambiar a POST para enviar FormData
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      $("#id_icono").val(response[0].id);
      $("#texto_icono").val(response[0].texto);
      $("#subTexto_icono").val(response[0].subtexto_icon);
      $("#enlace_icono").val(response[0].enlace_icon);
      $("#icono").val(response[0].icon_text).change();

      $("#editar_iconoModal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

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
  Swal.fire({
    title: "¿Estás seguro del nombre de tu tienda?",
    html: "<p>¡No se podrá cambiar el nombre de tu tienda en un futuro!</p><p id='mensaje-informativo'></p><div class='loader'></div>",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "¡Sí, Crear tienda!",
    cancelButtonText: "¡No, cancelar!",
    reverseButtons: true,
    showLoaderOnConfirm: true,
    allowOutsideClick: false, // No permitir cerrar el diálogo al hacer clic fuera
    allowEscapeKey: false, // No permitir cerrar el diálogo con la tecla Escape
    preConfirm: async () => {
      // Oculta los botones de confirmación y cancelación y elimina el título y mensaje inicial
      Swal.update({
        title: "", // Elimina el título
        icon: "",
        html: "<div id='gif-container'></div>", // Remueve el mensaje inicial y añade un contenedor para los GIFs
        showConfirmButton: false,
        showCancelButton: false,
      });

      var nombre_tienda = $("#nombre_tienda").val();

      // Muestra mensajes cada 10 segundos durante 2 minutos
      const mensajes = [
        "Esto tardará 2 minutos aproximadamente",
        "Se está creando su banner",
        "Estamos configurando su tienda",
        "Preparando todo para usted",
        "Últimos ajustes, casi listo",
        "Gracias por su paciencia",
        "Estamos finalizando el proceso",
        "Su tienda está casi lista",
        "Finalizando los últimos detalles",
      ];

      // Array con URLs de los GIFs
      const gifs = [
        SERVERURL + "public/img/gif/ICONOS_1.gif",
        SERVERURL + "public/img/gif/ICONOS_2.gif",
        SERVERURL + "public/img/gif/ICONOS_3.gif",
        SERVERURL + "public/img/gif/ICONOS_4.gif",
      ];

      let mensajeIndex = 0;
      let gifIndex = 0;

      // Inicia la llamada a la API inmediatamente
      let formData = new FormData();
      formData.append("nombre", nombre_tienda);

      let apiCall = $.ajax({
        url: SERVERURL + "Usuarios/registro",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
      });

      // Función para actualizar el GIF y el mensaje
      const updateGifAndMessage = () => {
        if (mensajeIndex < mensajes.length) {
          Swal.update({
            html: `
              <div id='gif-container'>
                <img src='${gifs[gifIndex]}' alt='Loading...' style='width:300px;height:300px;'><br>
                <p id='mensaje-informativo'>${mensajes[mensajeIndex]}</p>
              </div>
            `,
          });
          mensajeIndex++;
          gifIndex = (gifIndex + 1) % gifs.length; // Avanza al siguiente GIF, vuelve al primero si es el último
        }
      };

      updateGifAndMessage(); // Muestra el primer GIF y mensaje inmediatamente

      const intervalId = setInterval(updateGifAndMessage, 10000); // 10 segundos

      // Espera 2 minutos (120 segundos)
      await new Promise((resolve) => setTimeout(resolve, 120000));

      // Limpia el intervalo después de 2 minutos
      clearInterval(intervalId);

      // Espera la respuesta de la API
      return apiCall
        .then(
          function (response) {
            if (response.status == 500) {
              toastr.error(
                "LA IMAGEN NO SE AGREGÓ CORRECTAMENTE",
                "NOTIFICACIÓN",
                {
                  positionClass: "toast-bottom-center",
                }
              );
              Swal.showValidationMessage(
                `Error: La imagen no se agregó correctamente`
              );
            } else if (response.status == 200) {
              toastr.success("IMAGEN AGREGADA CORRECTAMENTE", "NOTIFICACIÓN", {
                positionClass: "toast-bottom-center",
              });
            }
          },
          function (jqXHR, textStatus, errorThrown) {
            Swal.showValidationMessage(`Request failed: ${errorThrown}`);
          }
        )
        .always(() => {
          // Recargar la página después de que termine el tiempo de carga y la API haya respondido
          location.reload();
        });
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

/* tabla de Testimonio */
let dataTableTestimonios;
let dataTableTestimoniosIsInitialized = false;

const dataTableTestimoniosOptions = {
  columnDefs: [
    {
      className: "centered",
      targets: [1, 2, 3, 4],
    },
    {
      orderable: false,
      targets: 0,
    }, //ocultar para columna 0 el ordenar columna
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

function editarTestimonio(id) {
  let formData = new FormData();
  formData.append("id", id);

  $.ajax({
    url: SERVERURL + "Usuarios/obtener_testimoniotiendaID",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      $("#id_testimonio").val(response[0].id_testimonio);
      $("#nombre_testimonioEditar").val(response[0].nombre);
      $("#testimonio_testimonioEditar").val(response[0].testimonio);
      $("#preview-imagen-testimonioEditar")
        .attr("src", SERVERURL + response[0].imagen)
        .show();
      $("#editar_testimonioModal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
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

    let formData = new FormData();
    formData.append("ruc", $("#ruc").val());

    formData.append("telefono_tienda", $("#whatsapp").val());
    formData.append("email_tienda", $("#email").val());
    formData.append("direccion_tienda", $("#direccion_tienda").val());
    formData.append("pais_tienda", $("#pais_tienda").val());
    formData.append("instagram", $("#instagram").val());
    formData.append("tiktok", $("#tiktok").val());
    formData.append("facebook", $("#facebook").val());

    $.ajax({
      url: SERVERURL + "Usuarios/actualizar_plataforma",
      type: "POST",
      data: formData,
      processData: false, // No procesar los datos
      contentType: false, // No establecer ningún tipo de contenido
      success: function (response) {
        response = JSON.parse(response);
        if (response.status == 500) {
          toastr.error("NO SE ACTUALIZO CORRECTAMENTE", "NOTIFICACIÓN", {
            positionClass: "toast-bottom-center",
          });
        } else if (response.status == 200) {
          toastr.success("SE ACTUALIZO CORRECTAMENTE", "NOTIFICACIÓN", {
            positionClass: "toast-bottom-center",
          });

          cambiosRealizados = false;
          botonFlotante.classList.remove("mostrar");
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert(errorThrown);
      },
    });
  });
});

/* Fin boton flotante de actualizar */
/* Tabla  horizontal */
let dataTableHorizonal;
let dataTableHorizonalIsInitialized = false;

const dataTableHorizonalOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3] },
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

const initDataTableHorizonal = async () => {
  if (dataTableHorizonalIsInitialized) {
    dataTableHorizonal.destroy();
  }

  await listHorizonal();

  dataTableHorizonal = $("#datatable_horizonal").DataTable(
    dataTableHorizonalOptions
  );

  dataTableHorizonalIsInitialized = true;
};

const listHorizonal = async () => {
  try {
    const response = await fetch(
      "" + SERVERURL + "Usuarios/obtener_horizontalTienda"
    );
    const horizonal = await response.json();

    let content = ``;
    let posicion = ``;
    let visible = ``;
    horizonal.forEach((item, index) => {
      if (item.posicion == 1) {
        posicion = `Arriba`;
      } else if (item.posicion == 2) {
        posicion = `Abajo`;
      }

      if (item.estado == 0) {
        visible = `NO`;
      } else if (item.estado == 1) {
        visible = `SI`;
      }
      content += `
                <tr>
                    <td>${item.texto}</td>
                    <td>${posicion}</td>
                    <td>${visible}</td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" style="cursor: pointer;" onclick="editar_horizontal(${item.id_horizontal})"><i class='fa-solid fa-pencil'></i>Editar</a></li>
                        <li><a class="dropdown-item" style="cursor: pointer;" onclick="eliminar_horizontal(${item.id_horizontal})"><i class='fa-solid fa-trash-can'></i>Eliminar</a></li>
                    </ul>
                    </div>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_horizonal").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableHorizonal();
});

function editar_horizontal(id) {
  let formData = new FormData();
  formData.append("id_horizontal", id);

  $.ajax({
    url: SERVERURL + "Usuarios/obtener_horizontaltiendaID",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      $("#id_horizontal").val(response[0].id_horizontal);
      $("#texto_flotanteEditar").val(response[0].texto);
      $("#visible_flotanteEditar").val(response[0].estado).change();
      $("#posicion_flotanteEditar").val(response[0].posicion).change();

      $("#editar_horizontalModal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

function eliminar_horizontal(id) {
  let formData = new FormData();
  formData.append("id_horizontal", id);

  $.ajax({
    type: "POST",
    url: SERVERURL + "Usuarios/eliminarHorizontal",
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

        initDataTableHorizonal();
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Hubo un problema al eliminar la categoría");
    },
  });
}
/* Fin tabla horizontal */

function abrir_agregar_dominio() {
  $("#agregar_dominioModal").modal("show");
}

function guardar_ofertas_plantilla2() {
  var titulo_oferta1 = $("#titulo_oferta1").val();
  var oferta1 = $("#oferta1").val();
  var descripcion_oferta1 = $("#descripcion_oferta1").val();
  var textoBtn_oferta1 = $("#textoBtn_oferta1").val();
  var enlace_oferta1 = $("#enlace_oferta1").val();
  var titulo_oferta2 = $("#titulo_oferta2").val();
  var oferta2 = $("#oferta2").val();
  var descripcion_oferta2 = $("#descripcion_oferta2").val();
  var textoBtn_oferta2 = $("#textoBtn_oferta2").val();
  var enlace_oferta2 = $("#enlace_oferta2").val();

  // Obtener los archivos de las imágenes
  var imagen1 = $("#imageInputOferta1")[0].files[0];
  var imagen2 = $("#imageInputOferta2")[0].files[0];

  let formData = new FormData();
  formData.append("titulo_oferta1", titulo_oferta1);
  formData.append("oferta1", oferta1);
  formData.append("descripcion_oferta1", descripcion_oferta1);
  formData.append("textoBtn_oferta1", textoBtn_oferta1);
  formData.append("enlace_oferta1", enlace_oferta1);
  formData.append("titulo_oferta2", titulo_oferta2);
  formData.append("oferta2", oferta2);
  formData.append("descripcion_oferta2", descripcion_oferta2);
  formData.append("textoBtn_oferta2", textoBtn_oferta2);
  formData.append("enlace_oferta2", enlace_oferta2);

  // Adjuntar las imágenes al FormData
  formData.append("imagen1", imagen1);
  formData.append("imagen2", imagen2);

  $.ajax({
    url: SERVERURL + "Usuarios/agregarOfertas",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("NO SE AGREGÓ CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("AGREGADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        $("#imagen_categoriaModal").modal("hide");
        initDataTable();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
  /* vista previa imagenes ofertas*/
  document
    .getElementById("imageInputOferta1")
    .addEventListener("change", function (event) {
      mostrarVistaPrevia(event, "imagen_oferta1");
    });

  document
    .getElementById("imageInputOferta2")
    .addEventListener("change", function (event) {
      mostrarVistaPrevia(event, "imagen_oferta2");
    });

  function mostrarVistaPrevia(event, imageId) {
    var reader = new FileReader();
    reader.onload = function () {
      var output = document.getElementById(imageId);
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  }
  /* vista previa imagenes ofertas*/
}
