let dataTableProductos;
let dataTableProductosIsInitialized = false;

const dataTableProductosOptions = {
  columnDefs: [
    { className: "centered", targets: [0, 1, 2, 3, 4, 5, 6] },
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

const initDataTableProductos = async () => {
  if (dataTableProductosIsInitialized) {
    dataTableProductos.destroy();
  }

  await listProductos();

  dataTableProductos = $("#datatable_productos").DataTable(dataTableProductosOptions);

  dataTableProductosIsInitialized = true;
};

const listProductos = async () => {
  try {
    const response = await fetch(
      "" + SERVERURL + "productos/obtener_productos"
    );
    const productos = await response.json();

    let content = ``;
    productos.forEach((producto, index) => {
      content += `
                <tr>
                    <td>${producto.id_producto}</td>
                    <td><i class="fas fa-camera icon-button" data-toggle="modal" data-target="#imagen_productoModal"></i></td>
                    <td>${producto.codigo_producto}</td>
                    <td>${producto.nombre_producto}</td>
                    <td>${producto.destacado}</td>
                    <td>${producto.saldo_stock}</td>
                    <td>${producto.costo_producto}</td>
                    <td>${producto.pcp}</td>
                    <td>${producto.pvp}</td>
                    <td>${producto.pref}</td>
                    <td>logo landing</td>
                    <td>logo agregar imagen</td>
                    <td>logo agregar a market</td>
                    <td>logo agregar atributos</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editarProducto(${producto.id_linea})"><i class="fa-solid fa-pencil"></i>Editar</button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarProducto(${producto.id_linea})"><i class="fa-solid fa-trash-can"></i>Borrar</button>
                    </td>
                </tr>`;
    });
    document.getElementById("tableBody_productos").innerHTML = content;
  } catch (ex) {
    alert(ex);
  }
};

window.addEventListener("load", async () => {
  await initDataTableProductos();
});
