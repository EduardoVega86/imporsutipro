/**
 * Variables globales y configuración DataTable
 */
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

/**
 * Inicializar DataTable
 */
const initDataTable = async () => {
  if (dataTableIsInitialized) {
    Datatable.destroy();
  }

  // 1) Llamamos al listado de bóvedas (cargamos dinámicamente el tbody)
  await listBovedas();

  // 2) Inicializamos DataTable
  Datatable = $("#datatable_bovedas").DataTable(dataTableOptions);
  dataTableIsInitialized = true;
};

/**
 * Listar bóvedas y construir las filas de la tabla
 */
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
          <td>
            ${
              boveda.ejemplo_landing
                ? `<a href="${boveda.ejemplo_landing}" target="_blank">Ver Landing</a>`
                : "N/A"
            }
          </td>
          <td>
            ${
              boveda.duplicar_funnel
                ? `<a href="${boveda.duplicar_funnel}" target="_blank">Ver Funnel</a>`
                : "N/A"
            }
          </td>
          <td>
            ${
              boveda.videos
                ? `<a href="${boveda.videos}" target="_blank">Ver Video</a>`
                : "N/A"
            }
          </td>
          <!-- Botón para editar -->
          <td>
            <button class="btn btn-warning btn-sm" onclick="editBoveda(${boveda.id_boveda})">
              Editar
            </button>
          </td>
        </tr>
      `;
    });

    document.getElementById("tableBody_bovedas").innerHTML = content;
  } catch (error) {
    console.error("Error al listar Bóvedas", error);
  }
};

/**
 * Cargar select de Nombres
 */
const cargarNombres = async () => {
  try {
    const response = await fetch(`${SERVERURL}Productos/obtener_productos_boveda`);
    const categorias = await response.json();

    let opciones = "<option value=''>Seleccione un Nombre</option>";
    categorias.forEach((cat) => {
      opciones += `<option value="${cat.id_producto}">${cat.nombre_producto}</option>`;
    });

    document.getElementById("nombreBoveda").innerHTML = opciones;
  } catch (error) {
    console.error("Error al cargar nombres:", error);
  }
};

/**
 * Cargar select de Categorías
 */
const cargarCategorias = async () => {
  try {
    const response = await fetch(`${SERVERURL}Productos/obtener_lineas_global`);
    const categorias = await response.json();

    let opciones = "<option value=''>Seleccione una Categoría</option>";
    categorias.forEach((cat) => {
      opciones += `<option value="${cat.id_linea}">${cat.nombre_linea}</option>`;
    });

    // Para el modal Agregar
    document.getElementById("categoriaBoveda").innerHTML = opciones;
    // Para el modal Editar
    document.getElementById("editCategoriaBoveda").innerHTML = opciones;
  } catch (error) {
    console.error("Error al cargar categorías:", error);
  }
};

/**
 * Cargar select de Proveedores
 */
const cargarProveedores = async () => {
  try {
    const response = await fetch(`${SERVERURL}Productos/obtenerProveedores`);
    const proveedores = await response.json();

    let opciones = "<option value=''>Seleccione un Proveedor</option>";
    proveedores.forEach((prov) => {
      opciones += `<option value="${prov.id_plataforma}">${prov.nombre_tienda}</option>`;
    });

    // Para el modal Agregar
    document.getElementById("proveedorBoveda").innerHTML = opciones;
    // Para el modal Editar
    document.getElementById("editProveedorBoveda").innerHTML = opciones;
  } catch (error) {
    console.error("Error al cargar proveedores:", error);
  }
};

/**
 * Función para Editar Bóveda
 */
const editBoveda = async (idBoveda) => {
  try {
    // 1) Obtener data de la bóveda por su ID
    const response = await fetch(`${SERVERURL}Productos/obtener_boveda_by_id/${idBoveda}`);
    const boveda = await response.json();

    // 2) Llenar el formulario en el modal Editar
    document.getElementById("editIdBoveda").value = boveda.id_boveda;
    document.getElementById("editNombreBoveda").value = boveda.nombre;
    document.getElementById("editCategoriaBoveda").value = boveda.id_linea;
    document.getElementById("editProveedorBoveda").value = boveda.id_plataforma;

    // Si usas Select2, refrescar el valor
    // $("#editCategoriaBoveda").trigger("change");
    // $("#editProveedorBoveda").trigger("change");

    // 3) Mostrar modal Editar
    const modal = new bootstrap.Modal(document.getElementById("modalEditarBoveda"));
    modal.show();

  } catch (error) {
    console.error("Error al obtener la Bóveda:", error);
  }
};

/**
 * Al cargar la ventana
 */
window.addEventListener("load", async () => {
  // 1) Inicializar la tabla
  await initDataTable();

  // 2) Cargar selects para AGREGAR
  await cargarNombres();
  await cargarCategorias();
  await cargarProveedores();

  // 3) Inicializar select2 (si lo usas) - Modal Agregar
  $("#nombreBoveda").select2({
    placeholder: "Seleccione un Nombre",
    allowClear: true,
    dropdownParent: $("#nombreBoveda"),
  });
  $("#categoriaBoveda").select2({
    placeholder: "Seleccione una Categoría",
    allowClear: true,
    dropdownParent: $("#modalAgregarBoveda"),
  });
  $("#proveedorBoveda").select2({
    placeholder: "Seleccione un Proveedor",
    allowClear: true,
    dropdownParent: $("#modalAgregarBoveda"),
  });

  // 4) Escuchar el submit del formulario "formAgregarBoveda"
  document.getElementById("formAgregarBoveda").addEventListener("submit", async (e) => {
    e.preventDefault(); // Evita recarga de página

    // Capturar datos del formulario
    const nombre = document.getElementById("nombreBoveda").value;
    const categoria = document.getElementById("categoriaBoveda").value;
    const proveedor = document.getElementById("proveedorBoveda").value;
    const ejemploLanding = document.getElementById("ejemploLanding").value;
    const duplicarFunnel = document.getElementById("duplicarFunnel").value;
    const videosBoveda = document.getElementById("videosBoveda").value;

    // Crear objeto con los datos
    let formData = new FormData();
    formData.append("nombre", nombre);
    formData.append("categoria", categoria);
    formData.append("proveedor", proveedor);
    formData.append("ejemploLanding", ejemploLanding);
    formData.append("duplicarFunnel", duplicarFunnel);
    formData.append("videosBoveda", videosBoveda);

    try {
      // Petición POST
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

        // Cerrar modal
        const modal = document.getElementById("modalAgregarBoveda");
        const modalBootstrap = bootstrap.Modal.getInstance(modal);
        modalBootstrap.hide();

        // Limpiar formulario
        document.getElementById("formAgregarBoveda").reset();

        // Recargar dataTable
        initDataTable();
      } else {
        Swal.fire({
          icon: "error",
          title: result.title,
          text: result.message,
        });
      }
    } catch (error) {
      console.error("Error al agregar Bóveda:", error);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudo procesar la solicitud",
      });
    }
  });

  // 5) Escuchar el submit del formulario "formEditarBoveda"
  document.getElementById("formEditarBoveda").addEventListener("submit", async (e) => {
    e.preventDefault();

    // Obtener valores
    const idBoveda = document.getElementById("editIdBoveda").value;
    const nombre = document.getElementById("editNombreBoveda").value;
    const categoria = document.getElementById("editCategoriaBoveda").value;
    const proveedor = document.getElementById("editProveedorBoveda").value;

    // Crear objeto FormData
    let formData = new FormData();
    formData.append("id_boveda", idBoveda);
    formData.append("nombre", nombre);
    formData.append("categoria", categoria);
    formData.append("proveedor", proveedor);

    try {
      // Petición POST para actualizar
      const response = await fetch(`${SERVERURL}Productos/actualizar_boveda`, {
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

        // Cerrar modal
        const modal = bootstrap.Modal.getInstance(document.getElementById("modalEditarBoveda"));
        modal.hide();

        // Recargar DataTable
        initDataTable();
      } else {
        Swal.fire({
          icon: "error",
          title: result.title,
          text: result.message,
        });
      }
    } catch (error) {
      console.error("Error al actualizar la Bóveda:", error);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudo procesar la solicitud",
      });
    }
  });

  // Si deseas usar Select2 para el modal Editar, puedes inicializarlo aquí:
  $("#editCategoriaBoveda").select2({
    placeholder: "Seleccione una Categoría",
    allowClear: true,
    dropdownParent: $("#modalEditarBoveda"),
  });

  $("#editProveedorBoveda").select2({
    placeholder: "Seleccione un Proveedor",
    allowClear: true,
    dropdownParent: $("#modalEditarBoveda"),
  });
});
