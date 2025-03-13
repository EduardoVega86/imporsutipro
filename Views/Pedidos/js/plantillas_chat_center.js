let dataTableObtenerUsuariosPlataforma;
let dataTableObtenerUsuariosPlataformaIsInitialized = false;

const dataTableObtenerUsuariosPlataformaOptions = {
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

const initDataTableObtenerUsuariosPlataforma = async () => {
  if (dataTableObtenerUsuariosPlataformaIsInitialized) {
    dataTableObtenerUsuariosPlataforma.destroy();
  }

  await listObtenerUsuariosPlataforma();

  dataTableObtenerUsuariosPlataforma = $(
    "#datatable_obtener_usuarios_plataforma"
  ).DataTable(dataTableObtenerUsuariosPlataformaOptions);

  dataTableObtenerUsuariosPlataformaIsInitialized = true;
};

const listObtenerUsuariosPlataforma = async () => {
  try {
    const response = await fetch(
      SERVERURL + "usuarios/obtener_plantillas_plataforma"
    );
    const obtenerUsuariosPlataforma = await response.json();

    let content = ``;
    let usuarioPrincipalId = null; // Guardar el ID del usuario principal

    // Buscar si hay un usuario con principal === 1
    usuarioPrincipalId =
      obtenerUsuariosPlataforma.find(
        (usuario) => parseInt(usuario.principal) === 1
      )?.id_template || null;

    obtenerUsuariosPlataforma.forEach((usuario) => {
      let editar = `<button class="btn btn-sm btn-primary" onclick="abrir_editar_usuario(${usuario.id_template})">
                      <i class="fa-solid fa-pencil"></i> Editar
                    </button>`;
      let eliminar = `<button class="btn btn-sm btn-danger" onclick="eliminar_plantilla(${usuario.id_template})">
                        <i class="fa-solid fa-trash-can"></i> Borrar
                      </button>`;

      // Lógica del checkbox
      let isChecked = parseInt(usuario.principal) === 1 ? "checked" : "";
      let isDisabled =
        usuarioPrincipalId !== null &&
        usuario.id_template !== usuarioPrincipalId
          ? "disabled"
          : "";

      // Si no hay usuario principal, todos los checkboxes deben estar habilitados
      if (usuarioPrincipalId === null) {
        isDisabled = "";
      }

      // Checkbox con evento onchange
      let checkbox = `<input type="checkbox" class="chk-usuario" ${isChecked} ${isDisabled} 
                        data-id="${usuario.id_template}" 
                        onchange="cambiarEstadoUsuario(this)">`;

      content += `
                <tr>
                <td>${usuario.id_template}</td>
                <td>${usuario.atajo}</td>
                <td>${usuario.mensaje}</td>
                <td>${checkbox}</td>
                <td>
                  ${editar}
                  ${eliminar}
                </td>
                </tr>`;
    });

    document.getElementById("tableBody_obtener_usuarios_plataforma").innerHTML =
      content;
  } catch (ex) {
    alert("Error: " + ex);
  }
};

// Función para cambiar el estado del usuario cuando se activa/desactiva el checkbox
const cambiarEstadoUsuario = async (checkbox) => {
  let id = checkbox.getAttribute("data-id");
  let estado = checkbox.checked ? 1 : 0;

  let formData = new FormData();
  formData.append("id_template", id);
  formData.append("estado", estado);

  try {
    const response = await fetch(SERVERURL + "usuarios/cambiar_estado", {
      method: "POST",
      body: formData,
    });

    const resultado = await response.json();

    if (resultado.status === 200) {
      initDataTableObtenerUsuariosPlataforma();
    } else {
      alert(resultado.title + ": " + resultado.message); // Mostrar error
      checkbox.checked = !checkbox.checked; // Revertir el cambio si falla
    }
  } catch (error) {
    alert("Error en la petición al servidor");
    checkbox.checked = !checkbox.checked; // Revertir el cambio si hay un error
  }
};

function abrir_modal_subirPlaca(id_usuario) {
  let formData = new FormData();
  formData.append("id_usuario", id_usuario); // Añadir el SKU al FormData
  $.ajax({
    url: SERVERURL + "usuarios/obtener_usuario",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      $("#id_usuario_matricula_licencia").val(id_usuario);

      if (response.matricula) {
        $("#imagePreviewmatricula")
          .attr("src", SERVERURL + response.matricula)
          .show();
      } else {
        $("#imagePreviewmatricula")
          .attr("src", SERVERURL + "public/img/broken-image.png")
          .show();
      }

      if (response.licencia) {
        $("#imagePreviewLicencia")
          .attr("src", SERVERURL + response.licencia)
          .show();
      } else {
        $("#imagePreviewLicencia")
          .attr("src", SERVERURL + "public/img/broken-image.png")
          .show();
      }

      $("#imagen_licencia_matriculaModal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

function eliminar_plantilla(id_template) {
  let formData = new FormData();
  formData.append("id_template", id_template);
  $.ajax({
    url: SERVERURL + "usuarios/eliminar_plantilla",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("ERROR AL ELIMINAR PLANTILLA", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("PLANTILLA ELIMINADA CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
        initDataTableObtenerUsuariosPlataforma();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

function abrir_editar_usuario(id_template) {
  let formData = new FormData();
  formData.append("id_template", id_template); // Añadir el SKU al FormData
  $.ajax({
    url: SERVERURL + "usuarios/obtener_template",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      $("#id_template_Editar").val(id_template);
      $("#atajo_Editar").val(response.atajo);
      $("#texto_Editar").val(response.mensaje);
      $("#editar_testimonioModal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

function abrir_editar_motorizado(id_usuario) {
  let formData = new FormData();
  formData.append("id_usuario", id_usuario); // Añadir el SKU al FormData
  $.ajax({
    url: SERVERURL + "usuarios/obtener_usuario",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      $("#id_usuario_editar_repartidor").val(id_usuario);
      $("#nombre_repartidor_editar").val(response.nombre_users);
      $("#celular_repartidor_editar").val(response.numero_motorizado);

      $("#editar_repartidorModal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert(errorThrown);
    },
  });
}

window.addEventListener("load", async () => {
  await initDataTableObtenerUsuariosPlataforma();

  await cargar_select_templates();
});

const cargar_select_templates = async () => {
  const url = SERVERURL + "Usuarios/obtener_templates_whatsapp";

  try {
    const response = await fetch(url, {
      method: "GET",
    });

    if (!response.ok) {
      throw new Error(`Error en la solicitud: ${response.status}`);
    }

    const data = await response.json();

    if (!Array.isArray(data)) {
      throw new Error("La respuesta de la API no es un array válido");
    }

    // Obtener y limpiar el select
    const select = $("#select_templates");
    select.empty().append('<option value="">Selecciona un template</option>');

    // Agregar las opciones
    data.forEach((template) => {
      select.append(new Option(template.nombre, template.id_template));
    });

    // Aplicar Select2 con dropdown dentro del modal
    select.select2({
      placeholder: "Selecciona un template",
      allowClear: true,
      width: "100%",
      dropdownParent: $("#configuraciones_chatcenterModal"),
    });
  } catch (error) {
    console.error("Error al cargar los templates:", error);
  }
};

function formatPhoneNumber(number) {
  // Eliminar caracteres no numéricos excepto el signo +
  number = number.replace(/[^\d+]/g, "");

  // Verificar si el número ya tiene el código de país +593
  if (/^\+593/.test(number)) {
    // El número ya está correctamente formateado con +593
    return number;
  } else if (/^593/.test(number)) {
    // El número tiene 593 al inicio pero le falta el +
    return "+" + number;
  } else {
    // Si el número comienza con 0, quitarlo
    if (number.startsWith("0")) {
      number = number.substring(1);
    }
    // Agregar el código de país +593 al inicio del número
    number = "+593" + number;
  }

  return number;
}
