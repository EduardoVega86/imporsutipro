$(document).ready(function () {
    $(".filter-btn").on("click", function () {
      $(".filter-btn").removeClass("active");
      $(this).addClass("active");
  
      var filtro_facturas = $(this).data("filter"); // Actualizar variable con el filtro seleccionado
  
      initDataTableAuditoria(filtro_facturas);
    });
  });
  
  window.addEventListener("load", async () => {
    await initDataTableAuditoria(0);
  });
  
  let dataTableAuditoria;
  let dataTableAuditoriaIsInitialized = false;
  
  const dataTableAuditoriaOptions = {
    columnDefs: [
      { className: "centered", targets: [0, 1, 2, 3, 4] },
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
  
  const initDataTableAuditoria = async (estado) => {
    if (dataTableAuditoriaIsInitialized) {
      dataTableAuditoria.destroy();
    }
  
    await listAuditoria(estado);
  
    dataTableAuditoria = $("#datatable_auditoria").DataTable(
      dataTableAuditoriaOptions
    );
  
    dataTableAuditoriaIsInitialized = true;
  };
  
  const listAuditoria = async (estado) => {
    try {
      const formData = new FormData();
      formData.append("estado", estado);
  
      const response = await fetch(
        "" + SERVERURL + "wallet/obtenerGuiasAuditoria",
        {
          method: "POST",
          body: formData,
        }
      );
      const auditoria = await response.json();
  
      let content = ``;
  
      auditoria.forEach((item, index) => {
  
        const codBtn = item.cod
          ? `<button class="btn-cod-si">SI</button>`
          : `<button class="btn-cod-no">NO</button>`;
  
        content += `
                  <tr>
                      <td>${item.numero_factura}</td>
                      <td>${item.numero_guia}</td>
                      <td>${codBtn}</td>
                      <td>${item.monto_factura}</td>
                      <td>${item.costo_flete}</td>
                      <td><input type="checkbox" class="selectCheckbox" data-id="${item.numero_factura}"></td>
                  </tr>`;
      });
      document.getElementById("tableBody_auditoria").innerHTML = content;
    } catch (ex) {
      alert(ex);
    }
  };
  