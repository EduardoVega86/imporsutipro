let dataTableListaUsuarioMatriz;
let dataTableListaUsuarioMatrizIsInitialized = false;

const dataTableListaUsuarioMatrizOptions = {
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

const initDataTableListaUsuarioMatriz = async () => {
  if (dataTableListaUsuarioMatrizIsInitialized) {
    dataTableListaUsuarioMatriz.destroy();
  }

  await listListaUsuarioMatriz();

  dataTableListaUsuarioMatriz = $("#datatable_lista_usuarioMatriz").DataTable(
    dataTableListaUsuarioMatrizOptions
  );

  dataTableListaUsuarioMatrizIsInitialized = true;
};

const listListaUsuarioMatriz = async () => {
  try {
    const response = await fetch(
      "" + SERVERURL + "usuarios/obtener_usuarios_matriz"
    );
    const listaUsuarioMatriz = await response.json();

    let content = ``;
    let checkboxState = "";
    let checkboxStateFull = "";
    listaUsuarioMatriz.forEach((usuario, index) => {
      // Verifica el valor de usuario.proveedor y ajusta el checkbox en consecuencia
      if (usuario.proveedor == 1) {
        checkboxState = "checked";
      } else {
        checkboxState = "";
      }

      if (usuario.full_f == 1) {
        checkboxStateFull = "checked";
      } else {
        checkboxStateFull = "";
      }

      let select_cargos = "";
      select_cargos = `
                    <select class="form-select select-cargos" style="max-width: 90%; margin-top: 10px;" data-id-users="${
                      usuario.id_users
                    }">
                        <option value="1" ${
                          usuario.cargo_users == 1 ? "selected" : ""
                        }>Por defecto</option>
                        <option value="10" ${
                          usuario.cargo_users == 10 ? "selected" : ""
                        }>Administrador global</option>
                        <option value="35" ${
                          usuario.cargo_users == 35 ? "selected" : ""
                        }>Repartidor</option>
                        <option value="5" ${
                          usuario.cargo_users == 5 ? "selected" : ""
                        }>Colaborador</option>
                        <option value="15" ${
                          usuario.cargo_users == 15 ? "selected" : ""
                        }>Estudiante premium</option>
                        <option value="20" ${
                          usuario.cargo_users == 20 ? "selected" : ""
                        }>Administrador boveda</option>
                    </select>`;

      content += `
                <tr>
                    <td>${usuario.id_users}</td>
                    <td>${usuario.nombre_users}</td>
                    <td>${usuario.usuario_users}</td>
                    <td>${usuario.email_users}</td>
                    <td>
                    <a href="https://wa.me/${formatPhoneNumber(
                      usuario.whatsapp
                    )}" target="_blank" style="font-size: 45px; vertical-align: middle; margin-left: 10px;">
                    <i class='bx bxl-whatsapp-square' style="color: green;"></i>
                    </a></td>
                    <td>${usuario.nombre_tienda}</td>
                    <td>${select_cargos}</td>
                    <td><input type="checkbox" class="selectCheckbox" data-id="${
                      usuario.id_users
                    }" ${checkboxState} onclick="toggleProveedor(${
        usuario.id_plataforma
      }, this.checked)"></td>
                    <td><input type="checkbox" class="selectCheckbox" data-id="${
                      usuario.id_users
                    }" ${checkboxStateFull} onclick="toggleFull(${
        usuario.id_plataforma
      }, this.checked)"></td>
                    <td>${usuario.date_added}</td>
                    <td><button class="btn btn-sm btn-primary" onclick="editarUsuario(${
                      usuario.id_users
                    })"><i class="fa-solid fa-pencil"></i>Cambiar Contraseña</button></td>
                </tr>`;
    });
    document.getElementById("tableBody_lista_usuarioMatriz").innerHTML =
      content;
  } catch (ex) {
    alert(ex);
  }
};

// Event delegation for select change
document.addEventListener("change", async (event) => {
  if (event.target && event.target.classList.contains("select-cargos")) {
    const id_user = event.target.getAttribute("data-id-users");
    const nuevoCargo = event.target.value;
    const formData = new FormData();
    formData.append("id_user", id_user);
    formData.append("cargo_nuevo", nuevoCargo);

    try {
      const response = await fetch(SERVERURL + `Usuarios/cambiar_cargo`, {
        method: "POST",
        body: formData,
      });
      const result = await response.json();
      if (result.status == 200) {
        toastr.success("ESTADO ACTUALIZADO CORRECTAMENTE", "NOTIFICACIÓN", {
          positionClass: "toast-bottom-center",
        });

        initDataTableListaUsuarioMatriz();
      }
    } catch (error) {
      console.error("Error al conectar con la API", error);
      alert("Error al conectar con la API");
    }
  }
});

// Función para manejar el evento click del checkbox
const toggleProveedor = async (userId, isChecked) => {
  const proveedorValue = isChecked ? 1 : 0;
  const formData = new FormData();
  formData.append("id_plataforma", userId);
  formData.append("proveedor", proveedorValue);

  try {
    const response = await fetch(`${SERVERURL}usuarios/agregarProveedor`, {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error("Error al actualizar el proveedor");
    }

    const result = await response.json();
    console.log("Proveedor actualizado:", result);
  } catch (error) {
    console.error("Error:", error);
    alert("Hubo un error al actualizar el proveedor");
  }
};

const toggleFull = async (userId, isChecked) => {
  const fullValue = isChecked ? 1 : 0;
  const formData = new FormData();
  formData.append("id_plataforma", userId);
  formData.append("proveedor", fullValue);

  try {
    const response = await fetch(`${SERVERURL}usuarios/agregarFull`, {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error("Error al actualizar el fullfilment");
    }

    const result = await response.json();
    console.log("fullfilment actualizado:", result);
  } catch (error) {
    console.error("Error:", error);
    alert("Hubo un error al actualizar el fullfilment");
  }
};

window.addEventListener("load", async () => {
  await initDataTableListaUsuarioMatriz();
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

function editarUsuario(id) {
  $("#id_usuarioCambiar").val(id);

  $("#cambiarClave_usuarioModal").modal("show");
}
