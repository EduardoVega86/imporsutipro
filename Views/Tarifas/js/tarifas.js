const urlActual = window.location.href;

const initDataTable = async () => {
  const response = await fetch(`${urlActual}/getTarifas`);
  const data = await response.json();

  $("#datatable_tarifas").DataTable({
    data: data,
    columns: [
      { data: "nombre" },
      { data: "descripcion" },
      { data: "precio" },
      {
        data: null,
        render: (data, type, row) => {
          return `
                        <a href="${urlActual}/editar/${row.id_tarifa}" class="btn btn-success">Editar</a>
                        <button class="btn btn-danger" onclick="eliminarTarifa(${row.id_tarifa})">Eliminar</button>
                    `;
        },
      },
    ],
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
  });

  $("#datatable_tarifas").on("click", "tr", function () {
    const data = $("#datatable_tarifas").DataTable().row(this).data();
    console.log(data);
  });
};

initDataTable();
