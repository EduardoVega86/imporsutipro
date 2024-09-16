const getFecha = () => {
  let fecha = new Date();
  let dia = fecha.getDate();
  let mes = fecha.getMonth() + 1;
  let anio = fecha.getFullYear();
  let hora = fecha.getHours();
  let minutos = fecha.getMinutes();
  let segundos = fecha.getSeconds();
  return `${dia}/${mes}/${anio} ${hora}:${minutos}:${segundos}`;
};

let dataTableHistorialSolicitudes = false;

const dataTableOptions = {
  columnDefs: [{ className: "text-center", targets: "_all" }],
  pageLength: 10,
  destroy: true,
  responsive: true,
  dom: '<"d-flex w-full justify-content-between"lBf><t><"d-flex justify-content-between"ip>',

  buttons: [
    {
      extend: "excelHtml5",
      text: 'Excel <i class="fa-solid fa-file-excel"></i>',
      title: "Panel de Control: Usuarios",
      titleAttr: "Exportar a Excel",

      filename: "acreditacion" + "_" + getFecha(),
      footer: true,
      className: "btn-excel",
    },
    {
      extend: "csvHtml5",
      text: 'CSV <i class="fa-solid fa-file-csv"></i>',
      title: "Panel de Control: acreditacion",
      titleAttr: "Exportar a CSV",
      filename: "acreditacion" + "_" + getFecha(),
      footer: true,
      className: "btn-csv",
    },
  ],

  languague: {
    url: "cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json",
  },
};

const listarHistorialSolicitudes = async () => {
  try {
    const response = await fetch(
      `${SERVERURL}wallet/historialSolicitudes`
    ).then((res) => res.json());

    console.log(response);
    if (response.status === 200) {
      console.log("tamos en el if");
      const data = response.data;
      const columns = [
        { data: "id_solicitud" },
        { data: "fecha" },
        { data: "tipo" },
        { data: "modal" },
        { data: "usuario" },
        { data: "cantidad" },
      ];

      dataTableHistorialSolicitudes = $("#datatable_historial").DataTable({
        ...dataTableOptions,
        data,
        columns,
      });
    }
  } catch (error) {
    console.error(error);
  }
};

const initDataTableHistorialSolicitudes = async () => {
  if (dataTableHistorialSolicitudes) {
    dataTableHistorialSolicitudes.destroy();
  }

  await listarHistorialSolicitudes();
};

const init = () => {
  initDataTableHistorialSolicitudes();
};

init();
