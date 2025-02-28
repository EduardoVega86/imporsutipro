/**
 * Variable que almacena la tabla de auditoria
 * @type {null}
 */
let dataTable = null;
let dataTableIsInit = false;

/**
 * @function getFecha
 * @description Función que retorna la fecha actual en formato "YYYY_MM_DD_HH_MM_SS
 * @returns {string}
 */
function getFecha() {
    let fecha = new Date();
    let mes = fecha.getMonth() + 1;
    let dia = fecha.getDate();
    let anio = fecha.getFullYear();
    let segundos = fecha.getSeconds();
    let minutos = fecha.getMinutes();
    let hora = fecha.getHours();
    return anio + "_" + mes + "_" + dia + "_" + hora + "_" + minutos + "_" + segundos;
}

/**
 * @type {{dom: string, order: (number|string)[][], pageLength: number, destroy: boolean, responsive: boolean, buttons: [{extend: string, text: string, title: string, titleAttr: string, exportOptions: {columns: number[]}, filename: string, footer: boolean, className: string},{extend: string, text: string, title: string, titleAttr: string, exportOptions: {columns: number[]}, filename: string, footer: boolean, className: string}], language: {lengthMenu: string, zeroRecords: string, info: string, infoEmpty: string, infoFiltered: string, search: string, loadingRecords: string, paginate: {first: string, last: string, next: string, previous: string}}}}
 * @description Opciones de la tabla de auditoria
 * @default
 * @property {string} dom - Estructura de la tabla
 * @property {number[][]} order - Ordenar por la primera columna (fecha) en orden descendente
 * @property {number} pageLength - Cantidad de registros por página
 * @property {boolean} destroy - Destruir la tabla
 * @property {boolean} responsive - Hacer la tabla responsiva
 * @property {Object[]} buttons - Botones de exportación
 * @property {string} buttons[].extend - Tipo de exportación
 * @property {string} buttons[].text - Texto del botón
 * @property {string} buttons[].title - Título de la exportación
 * @property {string} buttons[].titleAttr - Atributo title del botón
 * @property {Object} buttons[].exportOptions - Opciones de exportación
 * @property {number[]} buttons[].exportOptions.columns - Columnas a exportar
 * @property {string} buttons[].filename - Nombre del archivo
 * @property {boolean} buttons[].footer - Mostrar el footer
 * @property {string} buttons[].className - Clase del botón
 * @property {Object} language - Lenguaje de la tabla
 *
 */
const optionsDataTable = {
    columnDefs: [
        {
            className: "centered",
            targets: [0, 1, 2, 3, 4]
        },
    ],
    dom: '<"d-flex w-full justify-content-between"lBf><t><"d-flex justify-content-between"ip>',

    order: [[1, "desc"]], // Ordenar por la primera columna (fecha) en orden descendente
    pageLength: 10,
    destroy: true,
    responsive: true,
    buttons: [
        {
            extend: "excelHtml5",
            text: 'Excel <i class="fa-solid fa-file-excel"></i>',
            title: "Panel de Control: Auditoria",
            titleAttr: "Exportar a Excel",
            exportOptions: {
                columns: [1, 2, 4, 5, 6, 7, 8, 11, 12],
            },
            filename: "Auditoria" + "_" + getFecha(),
            footer: true,
            className: "btn-excel",
        },
        {
            extend: "csvHtml5",
            text: 'CSV <i class="fa-solid fa-file-csv"></i>',
            title: "Panel de Control: Auditoria",
            titleAttr: "Exportar a CSV",
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6, 7, 8],
            },
            filename: "Auditoria" + "_" + getFecha(),
            footer: true,
            className: "btn-csv",
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
    }
}
/**
 * @function initDataTable
 * @description Inicializa la tabla de auditoria
 * @returns {Promise<void>}
 */
const initDataTable = async () => {
    try {
        if (dataTableIsInit) {
            dataTable.destroy();
        }
        await listAuditables();
        dataTable = await $("#walletDatatable").DataTable(optionsDataTable);
        dataTableIsInit = true;
    } catch (e) {
        console.error(e);
    }
}

const listAuditables = async () => {
    try {
        const response = await fetch(SERVERURL + "auditoria/getAuditoria", {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        });
        const data = await response.json();
        let content = "";
        if (data.status === 200) {
            const auditables = data.data;
            for (let i = 0; i < auditables.length; i++) {
                const auditable = auditables[i];
                content += `
                    <tr>
                        <td>${auditable.id_auditorio}</td>
                        <td>${auditable.fecha}</td>
                        <td>${auditable.nombre_responsable}</td>
                        <td>${auditable.lugar}</td>
                        <td>${auditable.accion}</td>
                        <td>${auditable.servidor}</td>
                        
                    </tr>
                `;
            }

        } else {
            content += `
                <tr>
                    <td colspan="6" class="text-center">No hay registros</td>
                </tr>
            `;
        }
        $("#walletDatas").html(content);
    } catch (e) {
        console.error(e);
    }
}
window.addEventListener("load", async () => {
    await initDataTable()
});