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
      "" + SERVERURL + "usuarios/obtener_plantillas_plataforma"
    );
    const obtenerUsuariosPlataforma = await response.json();

    let content = ``;

    obtenerUsuariosPlataforma.forEach((usuario, index) => {
      let editar = "";
      let placa = "";
   
        editar = `<button class="btn btn-sm btn-primary" onclick="abrir_editar_usuario(${usuario.id_template})"><i class="fa-solid fa-pencil"></i>Editar</button>`;
      

      content += `
                <tr>
                <td>${usuario.id_template}</td>
                <td>${usuario.atajo}</td>
                <td>${usuario.mensaje}</td>
                
                <td>
                ${editar}
                <button class="btn btn-sm btn-danger" onclick="eliminar_usuario(${
                  usuario.id_users
                })"><i class="fa-solid fa-trash-can"></i>Borrar</button>
                </td>
                </tr>`;
    });
    document.getElementById("tableBody_obtener_usuarios_plataforma").innerHTML =
      content;
  } catch (ex) {
    alert(ex);
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

function eliminar_usuario(id_usuario) {
  let formData = new FormData();
  formData.append("id_usuario", id_usuario); // Añadir el SKU al FormData
  $.ajax({
    url: SERVERURL + "usuarios/eliminar_usuario",
    type: "POST",
    data: formData,
    processData: false, // No procesar los datos
    contentType: false, // No establecer ningún tipo de contenido
    dataType: "json",
    success: function (response) {
      if (response.status == 500) {
        toastr.error("LA IMAGEN NO SE AGREGRO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });
      } else if (response.status == 200) {
        toastr.success("IMAGEN AGREGADA CORRECTAMENTE", "NOTIFICACIÓN", {
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
        alert(id_template)
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
});

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
