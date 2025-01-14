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

  // Llamamos al listado de bovedas para cargar dinámicamente el tbody
  await listBovedas();

  // Inicializamos DataTable
  Datatable = $("#datatable_bovedas").DataTable(dataTableOptions);
  dataTableIsInitialized = true;
};

//Función que hace el fetch a controlador y pinta los datos en la tabla
const listBovedas = async () => {
  try {
    //Ruta donde hacemos la peticion
    const response = await fetch(`${SERVERURL}Productos/obtener_bovedas`);
    const bovedas = await response.json();

    let content = "";

    //Iteramos sobre el array de resultados
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
    console.error("Error al listar Bovedas", error);
  }
};



// Llenar select de Nombres
const cargarNombres = async () => {
  try {
    const response = await fetch(`${SERVERURL}Productos/obtener_productos_boveda`);
    const nombres = await response.json();

    let opciones = "<option value=''>Seleccione un Nombre</option>";
    nombres.forEach((cat) => {
      opciones += `<option value="${cat.id_producto}">${cat.nombre_producto}</option>`;
    });

    document.getElementById("nombreBoveda").innerHTML = opciones;
  } catch (error) {
    console.error("Error al cargar nombres:", error);
  }
};

// Llenar select de Categorías
const cargarCategorias = async () => {
  try {
    const response = await fetch(`${SERVERURL}Productos/obtener_lineas_global`);
    const categorias = await response.json();

    let opciones = "<option value=''>Seleccione una Categoría</option>";
    categorias.forEach((cat) => {
      opciones += `<option value="${cat.id_linea}">${cat.nombre_linea}</option>`;
    });

    document.getElementById("categoriaBoveda").innerHTML = opciones;
  } catch (error) {
    console.error("Error al cargar categorías:", error);
  }
};

// Llenar select de Proveedores
const cargarProveedores = async () => {
  try {
    const response = await fetch(`${SERVERURL}Productos/obtenerProveedores`);
    const proveedores = await response.json();

    let opciones = "<option value=''>Seleccione un Proveedor</option>";
    proveedores.forEach((prov) => {
      opciones += `<option value="${prov.id_plataforma}">${prov.nombre_tienda}</option>`;
    });

    document.getElementById("proveedorBoveda").innerHTML = opciones;
  } catch (error) {
    console.error("Error al cargar proveedores:", error);
  }
};

// Cuando cargue la ventana
window.addEventListener("load", async () => {
  // Inicializamos la tabla
  await initDataTable();

  // 1) Cargamos nombnres
  await cargarNombres();
  //2) inicializamos Select2 para nombre
  $("#nombreBoveda").select2({
    placeholder: "Seleccione un Nombre",
    allowClear: true,
    //Como esta dentro de un modal
    dropdownParent: $("#modalAgregarBoveda"),
  });

  // 1) Cargamos categorías
  await cargarCategorias();
  // 2) Ahora sí, inicializamos Select2 para categoría
  $("#categoriaBoveda").select2({
    placeholder: "Seleccione una Categoría",
    allowClear: true,
    // Si está dentro de un modal:
    dropdownParent: $("#modalAgregarBoveda"),
  });

  // 1) Cargamos proveedores
  await cargarProveedores();
  // 2) Inicializamos Select2 para proveedor
  $("#proveedorBoveda").select2({
    placeholder: "Seleccione un Proveedor",
    allowClear: true,
    // Si está dentro de un modal:
    dropdownParent: $("#modalAgregarBoveda"),
  });

  // Escuchar el submit del formulario "formAgregarBoveda"
  document
    .getElementById("formAgregarBoveda")
    .addEventListener("submit", async (e) => {
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
        console.error("Error al agregar Boveda:", error);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "No se pudo procesar la solicitud",
        });
      }
    });

  //Evento para capturar el boton  Ediar y abrir el modal
  document.addEventListener("click", async (e) => {
    if (e.target.classList.contains("btn-edit")) {
      const idBoveda = e.target.getAttribute("data-id");

      try {
        // Obtener los datos de la bóveda
        const response = await fetch(`${SERVERURL}Productos/obtener_boveda`, {
          method: "POST",
          body: JSON.stringify({ id_boveda: idBoveda }),
          headers: {
            "Content-Type": "application/json",
          },
        });
        const boveda = await response.json();

        if (boveda) {
          // Cargar los datos en el modal
          document.getElementById("editNombreBoveda").value = boveda.nombre;
          document.getElementById("editCategoriaBoveda").value = boveda.categoria;
          document.getElementById("editProveedorBoveda").value = boveda.proveedor;
          document.getElementById("editEjemploLanding").value = boveda.ejemplo_landing;
          document.getElementById("editDuplicarFunnel").value = boveda.duplicar_funnel;
          document.getElementById("editVideosBoveda").value = boveda.videos;

          // Mostrar modal
          const modal = new bootstrap.Modal(
            document.getElementById("modalEditarBoveda")
          );
          modal.show();
        }
      } catch (error) {
        console.error("Error al obtener bóveda:", error);
      }
    }
  });

  document.getElementById("formEditarBoveda").addEventListener("submit", async (e) => {
    e.preventDefault();
  
    const idBoveda = document.querySelector(".btn-edit[data-id]").getAttribute("data-id");
    const formData = new FormData();
    formData.append("id_boveda", idBoveda);
    formData.append("nombre", document.getElementById("editNombreBoveda").value);
    formData.append("categoria", document.getElementById("editCategoriaBoveda").value);
    formData.append("proveedor", document.getElementById("editProveedorBoveda").value);
    formData.append("ejemploLanding", document.getElementById("editEjemploLanding").value);
    formData.append("duplicarFunnel", document.getElementById("editDuplicarFunnel").value);
    formData.append("videos", document.getElementById("editVideosBoveda").value);
  
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
  
        // Cerrar modal y recargar tabla
        const modal = document.getElementById("modalEditarBoveda");
        const modalBootstrap = bootstrap.Modal.getInstance(modal);
        modalBootstrap.hide();
        initDataTable();
      } else {
        Swal.fire({
          icon: "error",
          title: result.title,
          text: result.message,
        });
      }
    } catch (error) {
      console.error("Error al editar bóveda:", error);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudo procesar la solicitud",
      });
    }
  });
});
