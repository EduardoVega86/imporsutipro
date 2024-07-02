let dataTableListaDevoluciones;
let dataTableListaDevolucionesIsInitialized = false;

const dataTableListaDevolucionesOptions = {
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

const initDataTableListaDevoluciones = async () => {
  if (dataTableListaDevolucionesIsInitialized) {
    dataTableListaDevoluciones.destroy();
  }

  await listListaDevoluciones();

  dataTableListaDevoluciones = $("#datatable_lista_devoluciones").DataTable(
    dataTableListaDevolucionesOptions
  );

  dataTableListaDevolucionesIsInitialized = true;
};

const listListaDevoluciones = async () => {
  try {
    const response = await fetch(
      "" + SERVERURL + "despacho/listarDevoluciones"
    );
    const listaDevoluciones = await response.json();

    let content = ``;

    listaDevoluciones.forEach((devolucion, index) => {
      content += `
                <tr>
                <td>${devolucion.id_relacion_devolucion}</td>
                <td>${devolucion.id_usuario}</td>
                <td>${devolucion.id_plataforma}</td>
                <td>${devolucion.id_transportadora}</td>
                <td>${devolucion.id_bodega}</td>
                <td>${devolucion.fecha_hora}</td>
                </tr>`;
    });
    document.getElementById("tableBody_lista_devoluciones").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

document
  .getElementById("generarDespachoBtn")
  .addEventListener("click", function () {
    const transportadoraSelect = document.getElementById("transportadora");
    const transportadoraValue = transportadoraSelect.value;

    if (transportadoraValue !== "-- Selecciona Transportadora --") {
      const url = `https://new.imporsuitpro.com/despacho/despacho?transportadora=${transportadoraValue}`;
      window.location.href = url;
    } else {
      alert("Por favor selecciona una transportadora.");
    }
  });

window.addEventListener("load", async () => {
  await initDataTableListaDevoluciones();
});
