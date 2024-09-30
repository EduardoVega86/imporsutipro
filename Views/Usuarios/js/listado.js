let dataTableObtenerUsuariosPlataforma;
let dataTableObtenerUsuariosPlataformaIsInitialized = false;

const dataTableObtenerUsuariosPlataformaOptions = {
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
      "" + SERVERURL + "usuarios/obtener_usuarios_plataforma"
    );
    const obtenerUsuariosPlataforma = await response.json();

    let content = ``;

    obtenerUsuariosPlataforma.forEach((usuario, index) => {
      let editar = "";
      let placa = "";
      if (usuario.cargo_users == 35){
        editar = `<button class="btn btn-sm btn-primary" onclick="abrir_editar_motorizado(${
          usuario.id_users
        })"><i class="fa-solid fa-pencil"></i>Editar</button>`;

        placa = `<i class="fa-solid fa-store" style='cursor:pointer' onclick="abrir_modal_subirPlaca(${
          usuario.id_users
        })"></i>`;
      } else {
        editar = `<button class="btn btn-sm btn-primary" onclick="abrir_editar_usuario(${
          usuario.id_users
        })"><i class="fa-solid fa-pencil"></i>Editar</button>`;
      }

      content += `
                <tr>
                <td>${usuario.id_users}</td>
                <td>${usuario.nombre_users}</td>
                <td>${usuario.usuario_users}</td>
                <td>${usuario.email_users}</td>
                <td>
                <a href="https://wa.me/${formatPhoneNumber(
                  usuario.whatsapp
                )}" target="_blank" style="font-size: 45px; vertical-align: middle; margin-left: 10px;" target="_blank">
                <i class='bx bxl-whatsapp-square' style="color: green;"></i>
                </a></td>
                <td>${usuario.nombre_tienda}</td>
                <td>${usuario.date_added}</td>
                <td>${placa}</td>
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

function abrir_editar_usuario(id_usuario) {
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
      $("#nombre_motorizado_detalle").text(response.data.nombre_motorizado);
      $("#numero_motorizado_detalle").text(response.data.numero_motorizado);

      $("#editar_usuarioModal").modal("show");
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
