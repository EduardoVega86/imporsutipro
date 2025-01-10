// Variables globales para DataTable
let Datatable;
let dataTableIsInitialized = false;

/** Opciones de DataTable */
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

/**
 * Inicializa la DataTable
 */
const initDataTable = async () => {
  // Si ya existe la DataTable, la destruimos
  if (dataTableIsInitialized) {
    Datatable.destroy();
  }

  // Carga la lista de bovedas y puebla el tbody
  await listBovedas();

  // Crea la instancia de DataTable
  Datatable = $("#datatable_bovedas").DataTable(dataTableOptions);

  // Marcamos que está inicializada
  dataTableIsInitialized = true;
};

/**
 * Hace fetch a "Productos/obtener_bovedas" y pinta datos en la tabla
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
          <td>${boveda.id_linea}</td>
          <td>${boveda.id_plataforma}</td>
          <td>${boveda.ejemplo_landing}</td>
          <td>${boveda.duplicar_funnel}</td>
          <td>${boveda.videos}</td>
        </tr>
      `;
    });

    document.getElementById("tableBody_bovedas").innerHTML = content;
  } catch (error) {
    console.error("Error al listar Bóvedas:", error);
  }
};

/**
 * Llenar select de Categorías
 */
const cargarCategorias = async () => {
  try {
    const response = await fetch(`${SERVERURL}Productos/obtener_lineas_global`);
    const categorias = await response.json();

    console.log("categorias =>", categorias); // Para debug

    let opciones = "<option value=''>Seleccione una Categoría</option>";
    categorias.forEach((cat) => {
      opciones += `<option value="${cat.id_linea}">${cat.nombre_linea}</option>`;
    });

    document.getElementById("categoriaBoveda").innerHTML = opciones;
  } catch (error) {
    console.error("Error al cargar categorías:", error);
  }
};

/**
 * Llenar select de Proveedores
 */
const cargarProveedores = async () => {
  try {
    const response = await fetch(`${SERVERURL}Productos/obtenerProveedores`);
    const proveedores = await response.json();

    console.log("proveedores =>", proveedores); // Para debug

    let opciones = "<option value=''>Seleccione un Proveedor</option>";
    proveedores.forEach((prov) => {
      opciones += `<option value="${prov.id_plataforma}">${prov.nombre_tienda}</option>`;
    });

    document.getElementById("proveedorBoveda").innerHTML = opciones;
  } catch (error) {
    console.error("Error al cargar proveedores:", error);
  }
};

/**
 * Inicializa Select2 en ambos selects
 */
const initSelect2 = () => {
  $("#categoriaBoveda").select2({
    placeholder: "Seleccione una Categoría",
    allowClear: true,
  });

  $("#proveedorBoveda").select2({
    placeholder: "Seleccione un Proveedor",
    allowClear: true,
  });
};

/**
 * Evento que se dispara al cargar la ventana
 */
window.addEventListener("load", async () => {
  // 1) Inicializar la DataTable
  await initDataTable();

  // 2) Cargar Categorías y Proveedores
  await cargarCategorias();
  await cargarProveedores();

  // 3) Ahora que ya hay <option>, inicializamos Select2
  initSelect2();

  // 4) Listener para el submit del formulario "Agregar Bóveda"
  document
    .getElementById("formAgregarBoveda")
    .addEventListener("submit", async (e) => {
      e.preventDefault();

      // Capturar datos del formulario
      const nombre = document.getElementById("nombreBoveda").value;
      const categoria = document.getElementById("categoriaBoveda").value;
      const proveedor = document.getElementById("proveedorBoveda").value;
      const ejemploLanding = document.getElementById("ejemploLanding").value;
      const duplicarFunnel = document.getElementById("duplicarFunnel").value;
      const videosBoveda = document.getElementById("videosBoveda").value;

      // Crear objeto con los datos
      const formData = new FormData();
      formData.append("nombre", nombre);
      formData.append("categoria", categoria);
      formData.append("proveedor", proveedor);
      formData.append("ejemploLanding", ejemploLanding);
      formData.append("duplicarFunnel", duplicarFunnel);
      formData.append("videosBoveda", videosBoveda);

      try {
        // Enviar petición POST con fetch
        const response = await fetch(`${SERVERURL}Productos/agregar_boveda`, {
          method: "POST",
          body: formData,
        });

        const result = await response.json();

        if (result.status === 200) {
          // Éxito
          Swal.fire({
            icon: "success",
            title: result.title,
            text: result.message,
            showConfirmButton: false,
            timer: 2000,
          });

          // Cerrar modal (si usas Bootstrap 5)
          const modal = document.getElementById("modalAgregarBoveda");
          const modalBootstrap = bootstrap.Modal.getInstance(modal);
          modalBootstrap.hide();

          // Limpiar formulario
          document.getElementById("formAgregarBoveda").reset();

          // Recargar dataTable (vuelve a listar)
          initDataTable();
        } else {
          // Error
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
});
