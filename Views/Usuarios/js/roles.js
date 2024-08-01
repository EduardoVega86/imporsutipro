let dataTable;
let dataTableIsInitialized = false;

const dataTableOptions = {
  columnDefs: [
    { className: "centered", targets: [1, 2, 3, 4, 5, 6, 7, 8, 9] },
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

const initDataTable = async () => {
  if (dataTableIsInitialized) {
    dataTable.destroy();
  }

  await listGuias();

  dataTable = $("#datatable_guias").DataTable(dataTableOptions);

  dataTableIsInitialized = true;

  // Handle select all checkbox
  document.getElementById("selectAll").addEventListener("change", function () {
    const checkboxes = document.querySelectorAll(".selectCheckbox");
    checkboxes.forEach((checkbox) => (checkbox.checked = this.checked));
  });
};

const listGuias = async () => {
  try {
    const response = await fetch("" + SERVERURL + "pedidos/obtener_guias");
    const guias = await response.json();

    let content = ``;
    let impresiones = "";
    guias.forEach((guia, index) => {
      let transporte = guia.id_transporte;
      let transporte_content = "";
      if (transporte == 2) {
        transporte_content =
          '<span style="background-color: #28C839; color: white; padding: 5px; border-radius: 0.3rem;">SERVIENTREGA</span>';
      } else if (transporte == 1) {
        transporte_content =
          '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">LAAR</span>';
      } else if (transporte == 4) {
        if (MATRIZ == 2) {
          transporte_content =
            '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">MerkaLogistic</span>';
        } else if (MATRIZ == 1) {
          transporte_content =
            '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">SPEED</span>';
        }
      } else if (transporte == 3) {
        transporte_content =
          '<span style="background-color: red; color: white; padding: 5px; border-radius: 0.3rem;">GINTRACOM</span>';
      } else {
        transporte_content =
          '<span style="background-color: #E3BC1C; color: white; padding: 5px; border-radius: 0.3rem;">Guia no enviada</span>';
      }

      estado = validar_estado(guia.estado_guia_sistema);
      var span_estado = estado.span_estado;
      var estado_guia = estado.estado_guia;

      //tomar solo la ciudad
      let ciudadCompleta = guia.ciudad;
      let ciudadArray = ciudadCompleta.split("/");
      let ciudad = ciudadArray[0];

      let plataforma = procesarPlataforma(guia.plataforma);
      if (guia.impreso == 0) {
        impresiones = `<box-icon name='printer' color= "red"></box-icon>`;
      } else {
        impresiones = `<box-icon name='printer' color= "green"></box-icon>`;
      }
      content += `
         <tr>
         <td>${guia.pvp}</td>
         <td>${guia.pref}</td>
         <td>
          <button class="btn btn-sm btn-primary" onclick="editarguia(${guia.id_guia})"><i class="fa-solid fa-pencil"></i>Editar</button>
          <button class="btn btn-sm btn-danger" onclick="eliminarguia(${guia.id_guia})"><i class="fa-solid fa-trash-can"></i>Borrar</button>
         </td>
         </tr>`;
    });
    document.getElementById("tableBody_guias").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTable();
});
