let currentPage = 1; // Página actual
let rowsPerPage = 10; // Filas por página

const API_URL = `${SERVERURL}pedidos/obtener_guiasAdministrador3`;

// Función para obtener los datos desde la API
const fetchData = async (page, filters) => {
  const formData = new FormData();
  formData.append("fecha_inicio", filters.fecha_inicio);
  formData.append("fecha_fin", filters.fecha_fin);
  formData.append("estado", filters.estado);
  formData.append("drogshipin", filters.drogshipin);
  formData.append("transportadora", filters.transportadora);
  formData.append("impreso", filters.impreso);
  formData.append("despachos", filters.despachos);
  formData.append("page", page);
  formData.append("rows", rowsPerPage);

  try {
    const response = await fetch(API_URL, { method: "POST", body: formData });
    if (!response.ok) throw new Error("Error al cargar datos desde la API");
    return await response.json();
  } catch (error) {
    console.error(error);
    return [];
  }
};

// Renderizar tabla
const renderTable = (data) => {
  const tableBody = document.getElementById("tableBody_guias");
  tableBody.innerHTML = "";

  if (data.length === 0) {
    tableBody.innerHTML = `
      <tr>
        <td colspan="8" class="text-center text-gray-500">No se encontraron resultados</td>
      </tr>`;
    return;
  }

  data.forEach((guia, index) => {
    tableBody.innerHTML += `
      <tr>
        <td class="px-6 py-4">${index + 1 + (currentPage - 1) * rowsPerPage}</td>
        <td class="px-6 py-4">${guia.numero_guia || "N/A"}</td>
        <td class="px-6 py-4">${guia.nombre || "Sin Nombre"}</td>
        <td class="px-6 py-4">${guia.fecha_factura || "Sin Fecha"}</td>
        <td class="px-6 py-4">${guia.ciudad || "Sin Ciudad"}</td>
        <td class="px-6 py-4">${guia.estado_guia_sistema || "Desconocido"}</td>
        <td class="px-6 py-4">${guia.transporte || "Sin Transporte"}</td>
        <td class="px-6 py-4 flex gap-2">
          <button onclick="viewDetails('${guia.id_factura}')" class="text-blue-500 underline">Ver</button>
          <button onclick="anularGuia('${guia.numero_guia}')" class="text-red-500 underline">Anular</button>
        </td>
      </tr>`;
  });
};

// Función para manejar la paginación
const handlePagination = async () => {
  const filters = {
    fecha_inicio: fecha_inicio,
    fecha_fin: fecha_fin,
    estado: document.getElementById("estado_q").value,
    drogshipin: document.getElementById("tienda_q").value,
    transportadora: document.getElementById("transporte").value,
    impreso: document.getElementById("impresion").value,
    despachos: document.getElementById("despachos").value,
  };

  const data = await fetchData(currentPage, filters);
  renderTable(data.guias);

  // Actualizar número de página
  document.getElementById("currentPage").textContent = `Página: ${currentPage}`;
};

// Funciones de paginación
document.getElementById("prevPage").addEventListener("click", () => {
  if (currentPage > 1) {
    currentPage--;
    handlePagination();
  }
});

document.getElementById("nextPage").addEventListener("click", () => {
  currentPage++;
  handlePagination();
});

// Inicializar tabla y filtro
document.addEventListener("DOMContentLoaded", () => {
  handlePagination();

  // Actualizar tabla al cambiar los filtros
  document.querySelectorAll("#estado_q, #tienda_q, #transporte, #impresion, #despachos").forEach((filter) => {
    filter.addEventListener("change", () => {
      currentPage = 1; // Reiniciar a la primera página
      handlePagination();
    });
  });
});
