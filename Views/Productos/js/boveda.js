//Variables glob para controlar Datatable

let Datatable;
let dataTableIsInitialized = false;

const dataTableOptions = {
    pageLength: 10,
    destroy: true,
    responsive: true,

    language: {
        lenghtMenu: "Mostrar _MENU_ regigstros por página",
        zeroRecords: "No se encnotraron resultados",
        info: "Mostrando de _START_ a _END_ de _TOTAL_ registros",
        infoEmpy:"No hay datos dsponibles",
        infoFiltered:"(filtrado de _MAX_ registros en total)",
        search: "Buscar",
        loadingRecords: "Cargando...",
        paginate:{
            first: "Primero",
            last: "Ùltimo",
            next: "Siguiente",
            previous: "Anterior",
        },
    },
};

//Inicializamos DataTable
const initDataTable = async () =>{
    //si ya fue inicializada, destruimos y volvemos a crear
    if (dataTableIsInitialized){
        dataTableIsInitialized.destroy();
    }

    //llamamos al listado de bovedas para cargar dinamicamente al tbody
    await listBovedas();

    //Inicializamos la datatable sobre la tabla con id = "datatable_bovedas"
    Datatable = $("#datatable_bovedas").DataTable(dataTableOptions);

    //Maracamos como inicializada
    dataTableIsInitialized= true;
};

//Función que hace el fetch a controlador y pinta los datos en la tabla

const listBovedas = async ()=>{
    try{
        //Ruta donde hacemos la peticion
        const response = await fetch(`${SERVERURL}Productos/obtener_bovedas`)
        const bovedas = await response.json();

        let content = ``;

        //Iteramos sober e array de resultados

        bovedas.forEach((boveda)=>{
            content +=`
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

    //Inyectamos las filas en el cuerpo de la tabla
        document.getElementById("tableBody_bovedas").innerHTML = content;
    } catch(error){
        console.error("Error al listar Bovedas", error);
    }
};

//Cuando cargue la ventana se inicialzia Datatable
window.addEventListener("load", async()=>{
    await initDataTable();
    await cargarCategorias();
    await cargarProveedores();

        // Escuchar el submit del formulario "formAgregarBoveda"
        document.getElementById("formAgregarBoveda").addEventListener("submit", async (e) => {
            e.preventDefault(); // Evita que se recargue la página
            
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
            const response = await fetch(`${SERVERURL}Productos/obtener_proveedores`);
            const proveedores = await response.json();
        
            let opciones = "<option value=''>Seleccione un Proveedor</option>";
            proveedores.forEach((prov) => {
                opciones += `<option value="${prov.id_proveedor}">${prov.nombre_proveedor}</option>`;
            });
        
            document.getElementById("proveedorBoveda").innerHTML = opciones;
            } catch (error) {
            console.error("Error al cargar proveedores:", error);
            }
        };
  

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
            // Enviar peticion POST con fetch
            const response = await fetch(`${SERVERURL}Productos/agregar_boveda`, {
                method: "POST",
                body: formData
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

                // Recargar dataTable
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
            console.error("Error al agregar Boveda:", error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "No se pudo procesar la solicitud",
            });
        }
    });
})