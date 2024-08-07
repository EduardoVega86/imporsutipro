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
  });

  $("#datatable_tarifas").on("click", "tr", function () {
    const data = $("#datatable_tarifas").DataTable().row(this).data();
    console.log(data);
  });
};

initDataTable();
