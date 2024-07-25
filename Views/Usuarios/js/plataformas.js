let dataTablePlataformas;
let dataTablePlataformasIsInitialized = false;

const dataTablePlataformasOptions = {
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

const initDataTablePlataformas = async () => {
  if (dataTablePlataformasIsInitialized) {
    dataTablePlataformas.destroy();
  }

  await listPlataformas();

  dataTablePlataformas = $("#datatable_plataformas").DataTable(
    dataTablePlataformasOptions
  );

  dataTablePlataformasIsInitialized = true;
};

const listPlataformas = async () => {
  try {
    const response = await fetch("" + SERVERURL + "wallet/obtenerDatos");
    const plataformas = await response.json();

    let content = ``;

    plataformas.forEach((plataforma, index) => {
      // Verifica el valor de usuario.proveedor y ajusta el checkbox en consecuencia
      if (plataforma.proveedor == 1) {
        checkboxState = "checked";
      } else {
        checkboxState = "";
      }
      content += `
                <tr>
                    <td>${plataforma.id_plataforma}</td>
                    <td>${plataforma.nombre_tienda}</td>
                    <td>${plataforma.contacto}</td>
                    <td>${plataforma.whatsapp}</td>
                    <td>${plataforma.url_imporsuit}</td>
                    <td>${plataforma.email}</td>
                    <td><input type="checkbox" class="selectCheckbox" data-id="${plataforma.id_users}" ${checkboxState} onclick="toggleProveedor(${plataforma.id_plataforma}, this.checked)"></td>
                </tr>`;
    });
    document.getElementById("tableBody_plataformas").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

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

window.addEventListener("load", async () => {
  await initDataTablePlataformas();
});
