<?php require_once './Views/templates/header.php'; ?>

<style>
    /* Contenedor principal */
    .full-screen-container {
        display: flex;
        justify-content: space-between; /* Distribuir elementos en los lados */
        align-items: flex-start; /* Alinear al inicio verticalmente */
        height: 100vh; /* Altura completa de la pantalla */
        padding: 20px; /* Espaciado interno */
        background-color: #f0f0f0; /* Color de fondo */
    }

    /* Estilo para el formulario */
    .custom-container-fluid {
        background-color: <?php echo COLOR_FONDO; ?>;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 45%; /* Ocupa el 45% del ancho */
    }

    .custom-container-fluid h1 {
        color: <?php echo COLOR_LETRAS; ?>;
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        color: <?php echo COLOR_LETRAS; ?>;
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    .form-group input::placeholder {
        color: #6c757d;
    }

    .btn {
        background-color: #198754;
        color: black;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
    }

    /* Estilo para la tabla */
    .guides-list-container {
        width: 50%; /* Ocupa el 50% del ancho */
    }

    .table {
        margin-top: 10px;
    }

    .cantidad-input {
        max-width: 80px; /* Limitar el tamaño del input de cantidad */
    }

    .delete-btn {
        color: red;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .delete-btn:hover {
        transform: scale(1.2);
    }

    @media (max-width: 768px) {
        /* Diseño para pantallas pequeñas */
        .full-screen-container {
            flex-direction: column;
            align-items: center;
        }

        .custom-container-fluid,
        .guides-list-container {
            width: 100%; /* Ocupa todo el ancho */
        }
    }
</style>

<div class="full-screen-container">
    <!-- Formulario -->
    <div class="custom-container-fluid">
        <h1>Despacho de producto</h1>
        <div class="form-group">
            <label for="numeroGuia">Escanee los productos que desea ingresar</label>
            <input type="text" id="numeroGuia" class="form-control" placeholder="Coloca el cursor aquí">
        </div>
        <button id="despachoBtn" class="btn btn-success mt-2">Despacho</button>
    </div>

    <!-- Tabla -->
    <div class="guides-list-container">
        <h2>Lista de productos</h2>
        <table id="guidesTable" class="table table-bordered">
            <thead>
                <tr>
                <th>ID</th>
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

    var bodega = getParameterByName('bodega');

    function ejecutarDespacho() {
        var numeroGuia = document.getElementById('numeroGuia').value;

        let formData = new FormData();
        //formData.append("bodega", bodega);

        $.ajax({
            type: "POST",
            url: SERVERURL + "Inventarios/generarIngresoProducto/" + numeroGuia,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                response = JSON.parse(response);
                if (response.status == 500) {
                    toastr.error("" + response.message, "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                } else if (response.status == 200) {
                    toastr.success("" + response.message, "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center",
                    });

                    let sku = response.sku || numeroGuia;
                    let id_inventario = response.id_inventario || id_inventario;
                    let nombreProducto = response.producto || "Producto genérico";
                    agregarProductoATabla(sku, nombreProducto, id_inventario);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
                alert("Hubo un problema al generar despacho");
            },
        });
    }

    function agregarProductoATabla(sku, nombreProducto, id_inventario) {
        var tableBody = document.querySelector('#guidesTable tbody');

        var filas = document.querySelectorAll('#guidesTable tbody tr');
        for (var i = 0; i < filas.length; i++) {
            var filaSku = filas[i].querySelector('.sku').textContent.trim();
            if (filaSku === sku) {
                var cantidadInput = filas[i].querySelector('.cantidad-input');
                var cantidadActual = parseInt(cantidadInput.value, 10);
                cantidadInput.value = cantidadActual + 1;
                toastr.success("Cantidad actualizada", "NOTIFICACIÓN", {
                    positionClass: "toast-bottom-center",
                });
                return;
            }
        }

        var nuevaFila = document.createElement('tr');
        nuevaFila.innerHTML = `
         <td class="id_inventario">${id_inventario}</td>
            <td class="sku">${sku}</td>
            <td>${nombreProducto}</td>
            <td>
                <input type="number" class="cantidad-input form-control" value="1" min="1">
            </td>
            <td>
                <button class="btn btn-danger btn-sm eliminarBtn">Eliminar</button>
            </td>
        `;

        nuevaFila.querySelector('.eliminarBtn').addEventListener('click', function() {
            eliminarProductoDeTabla(nuevaFila);
        });

        tableBody.appendChild(nuevaFila);
    }

    function eliminarProductoDeTabla(fila) {
        fila.remove();
        toastr.success("Producto eliminado de la tabla", "NOTIFICACIÓN", {
            positionClass: "toast-bottom-center",
        });
    }

    document.getElementById('numeroGuia').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            ejecutarDespacho();
        }
    });

    document.getElementById('despachoBtn').addEventListener('click', ejecutarDespacho);

    document.getElementById('generarImpresionBtn').addEventListener('click', function () {
    var productos = [];
    var filas = document.querySelectorAll('#guidesTable tbody tr');

    // Recorre las filas de la tabla
    filas.forEach(function (fila) {
        var id_inventario = fila.querySelector('.id_inventario').textContent.trim();
        var sku = fila.querySelector('.sku').textContent.trim();
        var nombreProducto = fila.cells[2].textContent.trim();
        var cantidad = fila.querySelector('.cantidad-input').value.trim();
        productos.push({ id_inventario, sku, nombreProducto, cantidad });
    });

    // Crear el JSON incluyendo la bodega
    var datos = {
        bodega: getParameterByName('bodega'), // Obtén la bodega desde el parámetro de la URL
        productos: productos,
    };

    var datosJSON = JSON.stringify(datos);

    console.log('Datos enviados:', datosJSON); // Verifica en la consola antes de enviar

    // Enviar la solicitud al servicio web
    $.ajax({
        url: '/Manifiestos/generarIngresoProducto', // Cambia la URL al endpoint correcto
        type: 'POST',
        contentType: 'application/json', // Indicamos que enviamos JSON
        data: datosJSON, // Enviamos el JSON completo
        success: function (response) {
            console.log('Respuesta del servidor:', response);
            alert('Productos enviados correctamente');
        },
        error: function (xhr, status, error) {
            console.error('Error al enviar los datos:', error);
            alert('Hubo un problema al enviar los productos');
        },
    });
});


</script>

<?php require_once './Views/templates/footer.php'; ?>
