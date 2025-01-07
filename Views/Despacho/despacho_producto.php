<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Despacho/css/despacho_style.php'; ?>

<style>
  
</style>


<div class="full-screen-container">
    <div class="custom-container-fluid mt-4" style="margin-right: 20px;">
        <h1>Despacho de productos</span></h1>
        <div class="form-group">
            <label for="numeroGuia">Escanee los productos que desea despachar</label>
            <input type="text" id="numeroGuia" placeholder="Coloca el cursor aquí antes de">
        </div>
        <button id="despachoBtn" class="btn btn-success">Despacho</button>
    </div>
    <div class="guides-list-container mt-4" style="margin-right: auto; margin-left: 30px;">
    <h2>Lista de productos</h2>
    <table id="guidesTable" class="table table-bordered">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Nombre del Producto</th>
                <th>Cantidad</th>
                <th>Acciones</th>
            </tr>

        </thead>
        <tbody>
            <!-- Filas dinámicas se agregarán aquí -->
        </tbody>
    </table>
    <div style="padding-top:10px;">
        <button id="generarImpresionBtn" class="btn btn-success">Generar Impresión</button>
    </div>
</div>
</div>


<script>
    // Función para obtener el valor de un parámetro de la URL
    function getParameterByName(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }

    // Obtener el valor del parámetro "transportadora"
   

    // Obtener el valor del parámetro "bodega"
    var bodega = getParameterByName('bodega');

    // Declarar un contador global para los números de guía en el listado
var contadorGuiasListado = 1;

function ejecutarDespacho() {
    var numeroGuia = document.getElementById('numeroGuia').value;

    // Simular obtener datos del producto del servidor
    let formData = new FormData();
    formData.append("bodega", bodega);

    $.ajax({
        type: "POST",
        url: SERVERURL + "Inventarios/generarDespachoProducto/" + numeroGuia,
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            response = JSON.parse(response);
            if (response.status == 500) {
                toastr.error(
                    "" + response.message,
                    "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    }
                );
            } else if (response.status == 200) {
                toastr.success("" + response.message, "NOTIFICACIÓN", {
                    positionClass: "toast-bottom-center",
                });

                // Datos ficticios para demostrar (esto lo obtendrás del servidor)
                let sku = response.sku || numeroGuia;
                let nombreProducto = response.nombre_producto || "Producto genérico";
                agregarProductoATabla(sku, nombreProducto);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud AJAX:", error);
            alert("Hubo un problema al generar despacho");
        },
    });
}

// Función para agregar un producto a la tabla
function agregarProductoATabla(sku, nombreProducto) {
    var tableBody = document.querySelector('#guidesTable tbody');

    // Verificar si el SKU ya está en la tabla
    var filas = document.querySelectorAll('#guidesTable tbody tr');
    for (var i = 0; i < filas.length; i++) {
        var filaSku = filas[i].querySelector('.sku').textContent.trim();
        if (filaSku === sku) {
            // Incrementar la cantidad si ya existe
            var cantidadElement = filas[i].querySelector('.cantidad');
            var cantidadActual = parseInt(cantidadElement.textContent, 10);
            cantidadElement.textContent = cantidadActual + 1; // Incrementar cantidad
            toastr.success("Cantidad actualizada", "NOTIFICACIÓN", {
                positionClass: "toast-bottom-center",
            });
            return; // Salir de la función
        }
    }

    // Crear una nueva fila si no existe
    var nuevaFila = document.createElement('tr');
    nuevaFila.innerHTML = `
        <td class="sku">${sku}</td>
        <td>${nombreProducto}</td>
        <td class="cantidad">1</td>
        <td>
            <button class="btn btn-danger btn-sm eliminarBtn">Eliminar</button>
        </td>
    `;

    // Agregar evento para eliminar fila
    nuevaFila.querySelector('.eliminarBtn').addEventListener('click', function() {
        eliminarProductoDeTabla(nuevaFila);
    });

    tableBody.appendChild(nuevaFila);
}

// Función para eliminar un producto de la tabla
function eliminarProductoDeTabla(fila) {
    fila.remove();
    toastr.success("Producto eliminado de la tabla", "NOTIFICACIÓN", {
        positionClass: "toast-bottom-center",
    });
}

// Escuchar el evento 'keypress' del input
document.getElementById('numeroGuia').addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        ejecutarDespacho();
    }
});

// Escuchar el evento 'click' del botón
document.getElementById('despachoBtn').addEventListener('click', ejecutarDespacho);

// Escuchar el evento 'click' del botón de generar impresión
document.getElementById('generarImpresionBtn').addEventListener('click', generarImpresion);

// Función para generar JSON con la tabla y enviarlo
function generarImpresion() {
    var button = document.getElementById("generarImpresionBtn");
    button.disabled = true; // Desactivar el botón

    var productos = [];
    var filas = document.querySelectorAll('#guidesTable tbody tr');
    filas.forEach(function(fila) {
        var sku = fila.querySelector('.sku').textContent.trim();
        var nombreProducto = fila.cells[1].textContent.trim();
        var cantidad = fila.querySelector('.cantidad').textContent.trim();
        productos.push({ sku, nombreProducto, cantidad });
    });

    var productosJSON = JSON.stringify(productos, null, 2);
    console.log(productosJSON);

    let formData = new FormData();
    formData.append("bodega", bodega);
    formData.append("productos", productosJSON);

    $.ajax({
        type: "POST",
        url: SERVERURL + "/Manifiestos/generarManifiesto",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(response) {
            if (response.status == 200) {
                const link = document.createElement("a");
                link.href = response.download;
                link.download = "";
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                button.disabled = false;
                window.location.href = '' + SERVERURL + 'despacho/lista_despachos';
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud AJAX:", error);
            alert("Hubo un problema al generar impresión");
        },
    });
}


    // Escuchar el evento 'click' del botón de generar impresión
    document.getElementById('generarImpresionBtn').addEventListener('click', generarImpresion);
</script>

<?php require_once './Views/templates/footer.php'; ?>