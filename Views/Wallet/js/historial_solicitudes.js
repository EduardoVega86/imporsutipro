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
  columnDefs: [{ className: "centered", targets: "_all" }],
  pageLength: 10,
  destroy: true,
  responsive: true,
  dom:
    "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
  buttons: [
    {
      extend: "copy",
      text: "Copiar",
      className: "btn btn-primary",
    },
    {
      extend: "excel",
      text: "Excel",
      className: "btn btn-primary",
    },
    {
      extend: "pdf",
      text: "PDF",
      className: "btn btn-primary",
    },
    {
      extend: "print",
      text: "Imprimir",
      className: "btn btn-primary",
    },
  ],

  languague: {
    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json",
  },
};

const listarHistorialSolicitudes = async () => {
  try {
    const response = await fetch(
      `${SERVERURL}wallet/historialSolicitudes`
    ).then((res) => res.json());

    console.log(response);
    if (response.status === 200) {
      const data = response.data;
      const columns = [
        { data: "id_solicitud" },
        { data: "fecha" },
        { data: "tipo" },
        { data: "modal" },
        { data: "usuario" },
        { data: "monto" },
      ];

      dataTableHistorialSolicitudes = $(
        "#dataTableHistorialSolicitudes"
      ).DataTable({
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
