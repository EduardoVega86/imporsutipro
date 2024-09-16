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
