let dataTableSeleccionProductoAtributo;
let dataTableSeleccionProductoAtributoIsInitialized = false;

const dataTableSeleccionProductoAtributoOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3] },
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

const initDataTableSeleccionProductoAtributo = async () => {
  if (dataTableSeleccionProductoAtributoIsInitialized) {
    dataTableSeleccionProductoAtributo.destroy();
  }

  await listGuiasSeleccionProductoAtributo();

  dataTableSeleccionProductoAtributo = $("#datatable_seleccionProductoAtributo").DataTable(
    dataTableSeleccionProductoAtributoOptions
  );

  dataTableSeleccionProductoAtributoIsInitialized = true;
};

const listGuiasSeleccionProductoAtributo = async () => {
  var id_productoSeleccionado = $("#id_productoSeleccionado").val();

  try {
    const response = await fetch(
      "" + SERVERURL + "productos/mostrarVariedades/" + id_productoSeleccionado
    );
    const seleccion_Protuctos = await response.json();

    let content = ``;
    seleccion_Protuctos.forEach((seleccion_Protucto, index) => {
      content += `
                <tr>
                    <td> ${seleccion_Protucto.nombre_atributo} ${seleccion_Protucto.variedad}</td>
                    <td>$ ${seleccion_Protucto.pcp}</td>
            <td>$ ${seleccion_Protucto.pvp}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="enviar_cliente(${seleccion_Protucto.id_producto},'${seleccion_Protucto.sku}',${seleccion_Protucto.pvp},${seleccion_Protucto.id_inventario})"><i class="fa-solid fa-plus"> </i></button>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_seleccionProductoAtributo").innerHTML =
      content;
  } catch (ex) {
    alert(ex);
  }
};
