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
// Validamos el estado antes de acceder a los datos se tuvo que encapsular en data por documentacion swagger debe mostrar codigo 200
// Función que hace el fetch a controlador y pinta los datos en la tabla
const listBovedas = async () => {
  try {
    // Ruta donde hacemos la petición
    const response = await fetch(`${SERVERURL}Productos/obtener_bovedas`);
    const result = await response.json();

    // Validamos el estado antes de acceder a los datos
    if (result.status === 200) {
      const bovedas = result.data;

      let content = "";

      // Iteramos sobre el array de resultados
      bovedas.forEach((boveda) => {
        content += `
          <tr>
            <td>${boveda.nombre}</td>
            <td>${boveda.categoria}</td>
            <td>${boveda.proveedor}</td>
            <td><img src="${SERVERURL + boveda.img}" alt="${boveda.nombre}" style="max-width: 100px; height: auto;"></td>
            <td><a href="${boveda.plantillas_ventas}" target="_blank" class="link-primary">Ver Mensajes</a></td>
            <td><a href="${boveda.ejemplo_landing}" target="_blank" class="link-primary">Ver Landing</a></td>
            <td><a href="${boveda.duplicar_funnel}" target="_blank" class="link-primary">Duplicar Funnel</a></td>
            <td><a href="${boveda.videos}" target="_blank" class="link-primary">Ver Video</a></td>
            <td><span class="">${boveda.fecha_create_at}</span></td>
            <td>
              <button class="btn btn-primary btn-sm btn-edit" onclick="abrirModalEditar(${boveda.id_boveda})">Editar</button>
            </td>
          </tr>
        `;
      });

      // Inyectamos las filas en el cuerpo de la tabla
      document.getElementById("tableBody_bovedas").innerHTML = content;
    } else {
      console.error("Error en la respuesta del servidor:", result.message);
    }
  } catch (error) {
    console.error("Error al listar Bovedas", error);
  }
};



// Llenar select de Nombres
const cargarNombres = async () => {
  try {
    const response = await fetch(
      `${SERVERURL}Productos/obtener_productos_todos`
    );
    const result = await response.json();

    // Validamos el estado antes de acceder a los datos se tuvo que encapsular en data por documentacion swagger debe mostrar codigo 200
    if (result.status === 200) {
      const nombres = result.data;

      let opciones = "<option value=''>Seleccione un Nombre</option>";
      nombres.forEach((cat) => {
        opciones += `<option value="${cat.id_producto}">${cat.nombre_producto}</option>`;
      });

      // Poblamos tanto el select de agregar como el de editar
      document.getElementById("nombreBoveda").innerHTML = opciones;
      document.getElementById("editNombreBoveda").innerHTML = opciones;
    } else {
      console.error("Error en la respuesta del servidor:", result.message);
    }
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

    // Poblamos tanto el select de agregar como el de editar
    document.getElementById("categoriaBoveda").innerHTML = opciones;
    document.getElementById("editCategoriaBoveda").innerHTML = opciones;
  } catch (error) {
    console.error("Error al cargar categorías:", error);
  }
};

// Llenar select de Proveedores
const cargarProveedores = async () => {
  try {
    const response = await fetch(`${SERVERURL}Productos/obtenerProveedores`);
    const result = await response.json();
    if(result.status ===200){
      const proveedores = result.data;

      let opciones = "<option value=''>Seleccione un Proveedor</option>";
      proveedores.forEach((prov) => {
        opciones += `<option value="${prov.id_plataforma}">${prov.nombre_tienda}</option>`;
      });
  
      // Poblamos tanto el select de agregar como el de editar
      document.getElementById("proveedorBoveda").innerHTML = opciones;
      document.getElementById("editProveedorBoveda").innerHTML = opciones;
    } else{
      console.error("Error en la respuesta del servidor:", result.message)
    }
  } catch (error) {
    console.error("Error al cargar proveedores:", error);
  }
};

// Delegar evento para el botón "Editar"
async function abrirModalEditar(id_boveda) {
  try {
    const response = await fetch(`${SERVERURL}Productos/obtenerBoveda/${id_boveda}`);

    if (!response.ok) {
      throw new Error(`Error del servidor: ${response.status}`);
    }

    const result = await response.json();

    // Verificar si los datos están en el formato esperado
    if (result.status === 200 && Array.isArray(result.data) && result.data.length > 0) {
      const boveda = result.data;

      // Configurar valores en el modal
      $("#editNombreBoveda").val(boveda[0].id_producto).trigger("change");
      $("#editCategoriaBoveda").val(boveda[0].id_linea).trigger("change");
      $("#editProveedorBoveda").val(boveda[0].id_plataforma).trigger("change");
      $("#editPlantillasVentas").val(boveda[0].plantillas_ventas);
      $("#editEjemploLanding").val(boveda[0].ejemplo_landing);
      $("#editDuplicarFunnel").val(boveda[0].duplicar_funnel);
      $("#editVideosBoveda").val(boveda[0].videos);
      $("#editar_idBoveda").val(boveda[0].id_boveda);

      // Dejar vacío el campo de archivo
      document.getElementById("Editarimagen").value = "";

      // Mostrar el modal
      $("#modalEditarBoveda").modal("show");
    } else {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: result.message || "No se encontraron datos para la bóveda.",
      });
    }
  } catch (error) {
    console.error("Error al obtener datos de la bóveda:", error);
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "No se pudieron cargar los datos de la bóveda.",
    });
  }
}

// Asegurarse de que el DOM esté cargado antes de ejecutar el código
document.addEventListener("DOMContentLoaded", () => {
  // Manejar el envío del formulario "formEditarBoveda"
  const formEditarBoveda = document.getElementById("formEditarBoveda");
  if (formEditarBoveda) {
    formEditarBoveda.addEventListener("submit", async (e) => {
      e.preventDefault(); // Evita recargar la página
      // Capturar datos del formulario
      const idBoveda = document.getElementById("editar_idBoveda").value;
      const nombre = document.getElementById("editNombreBoveda").value;
      const categoria = document.getElementById("editCategoriaBoveda").value;
      const proveedor = document.getElementById("editProveedorBoveda").value;

      const plantillaVentas =
        document.getElementById("editPlantillasVentas").value;
      const ejemploLanding =
        document.getElementById("editEjemploLanding").value;
      const duplicarFunnel =
        document.getElementById("editDuplicarFunnel").value;
      const videosBoveda = document.getElementById("editVideosBoveda").value;
      const imagenIn = document.getElementById("Editarimagen")

      const imagen = imagenIn.files[0];
      
      let formData = new FormData();
      formData.append("id_boveda", idBoveda);
      formData.append("id_producto", nombre);
      formData.append("id_linea", categoria);
      formData.append("id_plataforma", proveedor);
      formData.append("plantilla_ventas", plantillaVentas);
      formData.append("ejemplo_landing", ejemploLanding);
      formData.append("duplicar_funnel", duplicarFunnel);
      formData.append("videos", videosBoveda);
      formData.append("imagen", imagen)

      try {
        // Petición POST para editar la bóveda
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
          // Cerrar modal
          const modal = document.getElementById("modalEditarBoveda");
          const modalBootstrap = bootstrap.Modal.getInstance(modal);
          modalBootstrap.hide();
          // Limpiar formulario
          formEditarBoveda.reset();
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
        console.error("Error al editar la bóveda:", error);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "No se pudo procesar la solicitud.",
        });
      }
    });
  } else {
    console.error("El formulario 'formEditarBoveda' no existe en el DOM.");
  }
});

// Cuando cargue la ventana
window.addEventListener("load", async () => {
  // Inicializamos la tabla
  await initDataTable();

  // 1) Cargamos nombres y los asignamos a ambos selects
  await cargarNombres();
  // Inicializamos Select2 para nombre en agregar
  $("#nombreBoveda").select2({
    placeholder: "Seleccione un Nombre",
    allowClear: true,
    dropdownParent: $("#modalAgregarBoveda"),
  });

  // Inicializamos Select2 para nombre en editar
  $("#editNombreBoveda").select2({
    placeholder: "Seleccione un Nombre",
    allowClear: true,
    dropdownParent: $("#modalEditarBoveda"),
  });

  // 2) Cargamos categorías y los asignamos a ambos selects
  await cargarCategorias();
  // Inicializamos Select2 para categoría en agregar
  $("#categoriaBoveda").select2({
    placeholder: "Seleccione una Categoría",
    allowClear: true,
    dropdownParent: $("#modalAgregarBoveda"),
  });

  // Inicializamos Select2 para categoría en editar
  $("#editCategoriaBoveda").select2({
    placeholder: "Seleccione una Categoría",
    allowClear: true,
    dropdownParent: $("#modalEditarBoveda"),
  });

  // 3) Cargamos proveedores y los asignamos a ambos selects
  await cargarProveedores();
  // Inicializamos Select2 para proveedor en agregar
  $("#proveedorBoveda").select2({
    placeholder: "Seleccione un Proveedor",
    allowClear: true,
    dropdownParent: $("#modalAgregarBoveda"),
  });

  // Inicializamos Select2 para proveedor en editar
  $("#editProveedorBoveda").select2({
    placeholder: "Seleccione un Proveedor",
    allowClear: true,
    dropdownParent: $("#modalEditarBoveda"),
  });

  // Escuchar el submit del formulario "formAgregarBoveda"
  document
    .getElementById("formAgregarBoveda")
    .addEventListener("submit", async (e) => {
      e.preventDefault(); // Evita la recarga de la página

      // Capturar datos del formulario
      const nombre = document.getElementById("nombreBoveda").value;
      const categoria = document.getElementById("categoriaBoveda").value;
      const proveedor = document.getElementById("proveedorBoveda").value;
      const plantillaVentas = document.getElementById("plantillaVentas").value;
      const ejemploLanding = document.getElementById("ejemploLanding").value;
      const duplicarFunnel = document.getElementById("duplicarFunnel").value;
      const videosBoveda = document.getElementById("videosBoveda").value;
      const imagenInput = document.getElementById("imagen");
      const imagen = imagenInput.files[0];

      // Crear objeto FormData y agregar los campos
      let formData = new FormData();
      formData.append("id_producto", nombre);
      formData.append("categoria", categoria);
      formData.append("proveedor", proveedor);
      formData.append("imagen", imagen);
      formData.append("plantillaVentas", plantillaVentas);
      formData.append("ejemploLanding", ejemploLanding);
      formData.append("duplicarFunnel", duplicarFunnel);
      formData.append("videosBoveda", videosBoveda);

      console.log("FormData enviado:", ...formData.entries());

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
          if (modal) {
            const modalBootstrap = bootstrap.Modal.getInstance(modal);
            if (modalBootstrap) modalBootstrap.hide();
          }

          // Limpiar formulario
          document.getElementById("formAgregarBoveda").reset();


          // Limpiar los campos Select2
          $("#nombreBoveda").val(null).trigger("change");
          $("#categoriaBoveda").val(null).trigger("change");
          $("#proveedorBoveda").val(null).trigger("change");


          // Recargar DataTable
          initDataTable();
        } else {
          Swal.fire({
            icon: "error",
            title: result.title || "Error",
            text: result.message || "Ocurrió un error al agregar la bóveda.",
          });
        }
      } catch (error) {
        console.error("Error al agregar Boveda:", error);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "No se pudo procesar la solicitud.",
        });
      }
    });
});