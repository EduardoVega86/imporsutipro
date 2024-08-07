const urlActual = window.location.href;

const initDataTable = async () => {
  const response = await fetch(`${urlActual}/getTarifas`);
  const data = await response.json();

  $("#datatable_tarifas").DataTable({
    data: data,
    columns: [
      { data: "nombre" },
      { data: "precio" },
      { data: "descripcion" },
      {
        data: null,
        render: (data, type, row) => {
          return `
                        <a href="${urlActual}/editar/${row.id}" class="btn btn-success">Editar</a>
                        <button class="btn btn-danger" onclick="eliminarTarifa(${row.id})">Eliminar</button>
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
