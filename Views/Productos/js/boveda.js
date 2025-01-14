let Datatable;
let dataTableIsInitialized = false;

const dataTableOptions = {
  pageLength: 10,
  destroy: true,
  responsive: true,
  language: {
    lengthMenu: "Mostrar _MENU_ registros por página",
    zeroRecords: "No se encontraron resultados",
    info: "Mostrando de _START_ a _END_ de _TOTAL_ registros",
    infoEmpty: "No hay datos disponibles",
    infoFiltered: "(filtrado de _MAX_ registros en total)",
    search: "Buscar",
    loadingRecords: "Cargando...",
    paginate: {
      first: "Primero",
      last: "Último",
      next: "Siguiente",
      previous: "Anterior",
    },
  },
};

// Inicializar DataTable
const initDataTable = async () => {
  if (dataTableIsInitialized) {
    Datatable.destroy();
  }

  // Llamamos al listado de bóvedas para cargar dinámicamente el tbody
  await listBovedas();

  // Inicializamos DataTable
  Datatable = $("#datatable_bovedas").DataTable(dataTableOptions);
  dataTableIsInitialized = true;
};

//Función que hace el fetch a controlador y pinta los datos en la tabla
const listBovedas = async () => {
  try {
    const response = await fetch(`${SERVERURL}Productos/obtener_bovedas`);
    const bovedas = await response.json();

    let content = "";

    bovedas.forEach((boveda) => {
      content += `
        <tr>
          <td>${boveda.nombre}</td>
          <td>${boveda.categoria}</td>
          <td>${boveda.proveedor}</td>
          <td><a href="${boveda.ejemplo_landing}" target="_blank" class="link-primary">Ver Landing</a></td>
          <td><a href="${boveda.duplicar_funnel}" target="_blank" class="link-primary">Duplicar Funnel</a></td>
          <td><a href="${boveda.videos}" target="_blank" class="link-primary">Ver Video</a></td>
          <td>
            <button class="btn btn-primary btn-sm btn-edit" data-id="${boveda.id_boveda}">Editar</button>
          </td>
        </tr>
      `;
    });

    //Inyectamos las filas en el cuerpo de la tabla
    document.getElementById("tableBody_bovedas").innerHTML = content;
  } catch (error) {
    console.error("Error al listar Bóvedas", error);
  }
};

// Función para manejar la edición de una bóveda
const editarBoveda = async (idBoveda) => {
  console.log("Botón editar presionado");
  console.log("ID de bóveda:", idBoveda);

  try {
    const response = await fetch(`${SERVERURL}Productos/obtenerBoveda/${idBoveda}`);
    const boveda = await response.json();

    // Rellena el formulario del modal
    document.getElementById("editNombreBoveda").value = boveda.nombre || "";
    document.getElementById("editCategoriaBoveda").value = boveda.categoria || "";
    document.getElementById("editProveedorBoveda").value = boveda.proveedor || "";
    document.getElementById("editEjemploLanding").value = boveda.ejemplo_landing || "";
    document.getElementById("editDuplicarFunnel").value = boveda.duplicar_funnel || "";
    document.getElementById("editVideosBoveda").value = boveda.videos || "";

    // Muestra el modal de edición
    const modalEditarBoveda = new bootstrap.Modal(document.getElementById("modalEditarBoveda"));
    modalEditarBoveda.show();
  } catch (error) {
    console.error("Error al obtener datos de la bóveda:", error);
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "No se pudieron cargar los datos de la bóveda.",
    });
  }
};

// Manejar el envío del formulario de edición
document.getElementById("formEditarBoveda").addEventListener("submit", async (e) => {
  e.preventDefault();

  const idBoveda = document.getElementById("formEditarBoveda").dataset.id || "";
  const nombre = document.getElementById("editNombreBoveda").value;
  const categoria = document.getElementById("editCategoriaBoveda").value;
  const proveedor = document.getElementById("editProveedorBoveda").value;
  const ejemploLanding = document.getElementById("editEjemploLanding").value;
  const duplicarFunnel = document.getElementById("editDuplicarFunnel").value;
  const videosBoveda = document.getElementById("editVideosBoveda").value;

  const formData = new FormData();
  formData.append("id_boveda", idBoveda);
  formData.append("nombre", nombre);
  formData.append("categoria", categoria);
  formData.append("proveedor", proveedor);
  formData.append("ejemploLanding", ejemploLanding);
  formData.append("duplicarFunnel", duplicarFunnel);
  formData.append("videosBoveda", videosBoveda);

  try {
    const response = await fetch(`${SERVERURL}Productos/editar_boveda`, {
      method: "POST",
      body: formData,
    });

    const result = await response.json();

    if (result.status === 200) {
      Swal.fire({
        icon: "success",
        title: result.title,
        text: result.message,
        showConfirmButton: false,
        timer: 2000,
      });

      const modalEditarBoveda = bootstrap.Modal.getInstance(document.getElementById("modalEditarBoveda"));
      modalEditarBoveda.hide();

      await initDataTable();
    } else {
      Swal.fire({
        icon: "error",
        title: result.title,
        text: result.message,
      });
    }
  } catch (error) {
    console.error("Error al editar la bóveda:", error);
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "No se pudo procesar la solicitud.",
    });
  }
});

// Manejar el envío del formulario de agregar bóveda
document.getElementById("formAgregarBoveda").addEventListener("submit", async (e) => {
  e.preventDefault();

  const nombre = document.getElementById("nombreBoveda").value;
  const categoria = document.getElementById("categoriaBoveda").value;
  const proveedor = document.getElementById("proveedorBoveda").value;
  const ejemploLanding = document.getElementById("ejemploLanding").value;
  const duplicarFunnel = document.getElementById("duplicarFunnel").value;
  const videosBoveda = document.getElementById("videosBoveda").value;

  const formData = new FormData();
  formData.append("nombre", nombre);
  formData.append("categoria", categoria);
  formData.append("proveedor", proveedor);
  formData.append("ejemploLanding", ejemploLanding);
  formData.append("duplicarFunnel", duplicarFunnel);
  formData.append("videosBoveda", videosBoveda);

  try {
    const response = await fetch(`${SERVERURL}Productos/agregar_boveda`, {
      method: "POST",
      body: formData,
    });

    const result = await response.json();

    if (result.status === 200) {
      Swal.fire({
        icon: "success",
        title: result.title,
        text: result.message,
        showConfirmButton: false,
        timer: 2000,
      });

      const modalAgregarBoveda = bootstrap.Modal.getInstance(document.getElementById("modalAgregarBoveda"));
      modalAgregarBoveda.hide();

      document.getElementById("formAgregarBoveda").reset();
      await initDataTable();
    } else {
      Swal.fire({
        icon: "error",
        title: result.title,
        text: result.message,
      });
    }
  } catch (error) {
    console.error("Error al agregar bóveda:", error);
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "No se pudo procesar la solicitud.",
    });
  }
});

// Inicializar select2 para selects dinámicos
const initSelects = async () => {
  await cargarNombres();
  $("#nombreBoveda").select2({
    placeholder: "Seleccione un Nombre",
    allowClear: true,
    dropdownParent: $("#modalAgregarBoveda"),
  });

  await cargarCategorias();
  $("#categoriaBoveda").select2({
    placeholder: "Seleccione una Categoría",
    allowClear: true,
    dropdownParent: $("#modalAgregarBoveda"),
  });

  await cargarProveedores();
  $("#proveedorBoveda").select2({
    placeholder: "Seleccione un Proveedor",
    allowClear: true,
    dropdownParent: $("#modalAgregarBoveda"),
  });
};

// Cargar la tabla y los selects al cargar la ventana
window.addEventListener("load", async () => {
  await initDataTable();
  await initSelects();
});
