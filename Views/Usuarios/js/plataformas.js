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
    const response = await fetch(
      "" + SERVERURL + "Usuarios/obtener_plataformas"
    );
    const plataformas = await response.json();

    let content = ``;

    plataformas.forEach((plataforma) => {
      // Verifica el valor de plataforma.Existe y ajusta el checkbox en consecuencia
      const checkboxState = plataforma.Existe == 1 ? "checked" : "";
      content += `
        <tr>
          <td>${plataforma.id_plataforma}</td>
          <td>${plataforma.nombre_tienda}</td>
          <td>${plataforma.contacto}</td>
          <td>${plataforma.whatsapp}</td>
          <td>${plataforma.url_imporsuit}</td>
          <td>${plataforma.email}</td>
          <td><input type="checkbox" class="selectCheckbox" data-id="${plataforma.id_plataforma}" ${checkboxState} onclick="toggleProveedor(${plataforma.id_plataforma}, this.checked)"></td>
        </tr>`;
    });

    document.getElementById("tableBody_plataformas").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

// Función para manejar el evento click del checkbox
const toggleProveedor = async (idPlataforma, isChecked) => {
  const proveedorValue = isChecked ? 1 : 0;
  const formData = new FormData();
  formData.append("id_plataforma", idPlataforma); // Añadir id_plataforma
  //formData.append("proveedor", proveedorValue);

  try {
    const response = await fetch(`${SERVERURL}usuarios/quitarTienda`, {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error("Error al actualizar el proveedor");
    }

    const result = await response.json();
    if (result.status == 500) {
      toastr.error("El registro no se quito correctamente", "NOTIFICACIÓN", {
        positionClass: "toast-bottom-center",
      });
    } else if (result.status == 200) {
      toastr.success("El registros se quito correctamente", "NOTIFICACIÓN", {
        positionClass: "toast-bottom-center",
      });

      $("#imagen_categoriaModal").modal("hide");
      initDataTable();
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Hubo un error al actualizar el proveedor");
  }
};

window.addEventListener("load", async () => {
  await initDataTablePlataformas();
});
