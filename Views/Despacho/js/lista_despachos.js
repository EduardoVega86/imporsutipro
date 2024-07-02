let dataTableListaDespachos;
let dataTableListaDespachosIsInitialized = false;

const dataTableListaDespachosOptions = {
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

const initDataTableListaDespachos = async () => {
  if (dataTableListaDespachosIsInitialized) {
    dataTableListaDespachos.destroy();
  }

  await listListaDespachos();

  dataTableListaDespachos = $("#datatable_lista_despachos").DataTable(
    dataTableListaDespachosOptions
  );

  dataTableListaDespachosIsInitialized = true;
};

const listListaDespachos = async () => {
  try {
    const response = await fetch("" + SERVERURL + "despacho/listarDespachos");
    const listaDespachos = await response.json();

    let content = ``;

    listaDespachos.forEach((despacho, index) => {
      content += `
                  <tr>
                      <td>${despacho.id_relacion_despacho}</td>
                      <td>${despacho.id_usuario}</td>
                      <td>${despacho.id_plataforma}</td>
                      <td>${despacho.id_transportadora}</td>
                      <td>${despacho.id_bodega}</td>
                      <td>${despacho.fecha_hora}</td>
                  </tr>`;
    });
    document.getElementById("tableBody_lista_despachos").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableListaDespachos();
});
