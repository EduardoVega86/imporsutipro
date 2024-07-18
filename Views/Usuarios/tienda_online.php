<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Usuarios/css/tiendaOnline_style.php'; ?>

<?php require_once './Views/Usuarios/Modales/agregar_banner.php'; ?>
<?php require_once './Views/Usuarios/Modales/editar_banner.php'; ?>
<?php require_once './Views/Usuarios/Modales/agregar_testimonio.php'; ?>
<?php require_once './Views/Usuarios/Modales/editar_testimonio.php'; ?>

<style>
    .container {
        width: 90%;
        max-width: 1200px;
        background: #ffffff;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        padding: 30px;
    }

    .section {
        margin-bottom: 30px;
    }

    .section h2 {
        color: #333;
        font-size: 24px;
        margin-bottom: 20px;
    }

    .inputs {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .input-box {
        flex: 1 1 calc(33.333% - 20px);
        min-width: 100px;
        max-width: 200px;
        height: 150px;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .input-box input {
        width: 80%;
        height: 80%;
        border: none;
        outline: none;
        border-radius: 8px;
        cursor: pointer;
        -webkit-appearance: none;
        appearance: none;
    }

    .input-box input::-webkit-color-swatch-wrapper {
        padding: 0;
    }

    .input-box input::-webkit-color-swatch {
        border: none;
        border-radius: 8px;
    }

    .input-label {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        font-size: 14px;
        font-weight: bold;
        color: #333;
    }
</style>
<div class="custom-container-fluid mt-4">

    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    CONFIGURACIÓN PRINCIPAL
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="card-title">LOGO DE LA EMPRESA</h5>
                                    <form id="imageFormPrincipal" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <input type="file" class="form-control" id="imageInputPrincipal" accept="image/*" name="imagen">
                                        </div>
                                        <img id="imagen_logo" class="image-preview mb-3" src="" alt="Preview" width="50%">
                                    </form>
                                    <h5 class="card-title">FAVICON</h5>
                                    <!--form id="imageFormFavicon" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <input type="file" class="form-control" id="imageInputFav" accept="image/*" name="imagenFav">
                                        </div>
                                        <img id="imagePreviewFav" class="image-preview mb-3" src="" alt="Preview" width="200px">
                                    </form-->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h3 style="text-align-last: center;">INFORMACION DE LA TIENDA</h3>
                                    <div class="form-group mb-3">
                                        <label for="nombre_tienda">Nombre de la tienda:</label>
                                        <input type="text" class="form-control" id="nombre_tienda" name="nombre_tienda">

                                        <div id="tienda-error" style="color: red; display: none;">Esta tienda ya existe.</div>
                                        <div id="tienda-creada" style="color: red;"></div>
                                        <div class="alert alert-warning" id="seccion_nosePermiteTMP" style="display: none;" role="alert">
                                            <strong>Atención:</strong> Cambie su nombre de tienda, para proceder con la creación.
                                        </div>
                                        <div id="seccion_creacionTienda">
                                            <div class="alert alert-warning" role="alert">
                                                <strong>Atención:</strong> Antes de darle al boton "Crear tienda", verifique que el nombre de la tienda sea el deseado, ya que no se permitiran cambios de nombre en la tienda.
                                            </div>
                                        </div>
                                        <button id="crear_tienda" class="btn btn-success" onclick="crear_tienda()"><i class="fa-solid fa-shop"></i> Crear tienda</button>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="ruc">RUC</label>
                                        <input type="text" class="form-control cambio" id="ruc" name="ruc">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="telefono_tienda">Telefono:</label>
                                        <input type="text" class="form-control cambio" id="whatsapp" name="whatsapp">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="email_tienda">Email:</label>
                                        <input type="email" class="form-control cambio" id="email" name="email">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="direccion_tienda">Dirección:</label>
                                        <input type="text" class="form-control cambio" id="direccion" name="direccion">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="pais_tienda">-- Elige un país --</label>
                                        <select class="form-select cambio" id="pais_tienda" name="pais_tienda">
                                            <option selected value="EC">Ecuador</option>
                                            <!-- Agrega más opciones según sea necesario -->
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="" id="habilitarTextoFlotante" checked>
                        <label class="form-check-label" for="habilitarTextoFlotante">
                            Habilitar texto flotante
                        </label>
                    </div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Posición</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>34</td>
                            <td>COMPRA AHORA!!</td>
                            <td>Barra superior</td>
                            <td><span class="badge bg-success">Activo</span></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-warning btn-sm"><i class="bi bi-gear"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>36</td>
                            <td>COMPRA AHORA!!</td>
                            <td>Barra inferior</td>
                            <td><span class="badge bg-success">Activo</span></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-warning btn-sm"><i class="bi bi-gear"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Anterior</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="" id="habilitarEnvioGratis">
                        <label class="form-check-label" for="habilitarEnvioGratis">
                            Habilitar Envío Gratis en botón comprar ahora
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="" id="habilitarBotonWhatsapp" checked>
                        <label class="form-check-label" for="habilitarBotonWhatsapp">
                            Habilitar Botón de whatsapp
                        </label>
                    </div>
                </div>
            </div>
        </div> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    BANNER
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="justify-content-between align-items-center mb-3">
                        <div class="d-flex">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_bannerModal"><i class="fas fa-plus"></i> Agregar</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- <table class="table table-bordered table-striped table-hover"> -->
                        <table id="datatable_banner" width="100%" class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">Titulo</th>
                                    <th class="text-nowrap">Icono</th>
                                    <th class="text-nowrap">Subtexto</th>
                                    <th class="text-nowrap">Texto Boton</th>
                                    <th class="text-nowrap">Enlace Boton</th>
                                    <th class="text-nowrap">Alineacion</th>
                                    <th class="text-nowrap">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody_banner"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    ICONOS
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="table-responsive">
                        <!-- <table class="table table-bordered table-striped table-hover"> -->
                        <table id="datatable_caracteristicas" width="100%" class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">Titulo</th>
                                    <th class="text-nowrap">Icono</th>
                                    <th class="text-nowrap">Subtexto</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody_caracteristicas"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    COLORES
                </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="container">
                        <div class="section">
                            <h2>Elementos</h2>
                            <div class="inputs">
                                <div class="input-box">
                                    <input onchange="cambiarcolor('color_botones',this.value)" id="color_botones" name="color_botones" type="color" value="#ff0000">
                                    <div class="input-label">Botones</div>
                                </div>
                                <div class="input-box">
                                    <input onchange="cambiarcolor('color',this.value)" id="color" name="color" onchange="cambiarcolor()" type="color" value="#000000">
                                    <div class="input-label">Barra Superior</div>
                                </div>
                            </div>
                        </div>
                        <div class="section">
                            <h2>Textos</h2>
                            <div class="inputs">
                                <div class="input-box">
                                    <input onchange="cambiarcolor('texto_cabecera',this.value)" id="texto_cabecera" name="texto_cabecera" type="color" value="#ffffff">
                                    <div class="input-label">Cabecera</div>
                                </div>
                                <div class="input-box">
                                    <input onchange="cambiarcolor('texto_boton',this.value)" id="texto_boton1" name="texto_boton1" type="color" value="#ffffff">
                                    <div class="input-label">Botones</div>
                                </div>
                                <div class="input-box">
                                    <input onchange="cambiarcolor('texto_precio',this.value)" id="texto_precio" name="texto_precio" type="color" value="#000000">
                                    <div class="input-label">Texto Precio</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFive">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                    TESTIMONIOS
                </button>
            </h2>
            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="justify-content-between align-items-center mb-3">
                        <div class="d-flex">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_testimonioModal"><i class="fas fa-plus"></i> Agregar</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- <table class="table table-bordered table-striped table-hover"> -->
                        <table id="datatable_testimonios" width="100%" class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">Imagen</th>
                                    <th class="text-nowrap">Nombre</th>
                                    <th class="text-nowrap">Testimonio</th>
                                    <th class="text-nowrap">Fecha</th>
                                    <th class="text-nowrap">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody_testimonios"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSix">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                    REDES SOCIALES / FOOTER
                </button>
            </h2>
            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="mb-3">
                        <label for="instagram" class="form-label">Instagram</label>
                        <input type="text" class="form-control cambio" id="instagram" placeholder="Ingrese su Instagram">
                    </div>
                    <div class="mb-3">
                        <label for="tiktok" class="form-label">TikTok</label>
                        <input type="text" class="form-control cambio" id="tiktok" placeholder="Ingrese su TikTok">
                    </div>
                    <div class="mb-3">
                        <label for="facebook" class="form-label">Facebook</label>
                        <input type="text" class="form-control cambio" id="facebook" placeholder="Ingrese su Facebook">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button id="botonFlotante" class="boton-flotante">Guardar Cambios</button>
</div>




<script>
    $('#imageInputPrincipal').on('change', function(event) {
        event.preventDefault();

        // Mostrar vista previa de la imagen seleccionada
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagen_logo').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }

        // Crear un FormData y enviar la imagen mediante AJAX
        var formData = new FormData($('#imageFormPrincipal')[0]);
        $.ajax({
            url: SERVERURL + 'Usuarios/guardar_imagen_logo', // Cambia esta ruta por la ruta correcta a tu controlador
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                response = JSON.parse(response);
                if (response.status == 500) {
                    toastr.error("LA IMAGEN NO SE AGREGRO CORRECTAMENTE", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                } else if (response.status == 200) {
                    toastr.success("IMAGEN AGREGADA CORRECTAMENTE", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center",
                    });
                    $('#imagen_productoModal').modal('hide');
                    reloadDataTableProductos();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error al guardar la imagen: ' + textStatus);
            }
        });
    });


    $('#imageInputFav').on('change', function(event) {
        event.preventDefault();

        // Mostrar vista previa de la imagen seleccionada
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagen_logo').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }

        // Crear un FormData y enviar la imagen mediante AJAX
        var formData = new FormData($('#imageFormFavicon')[0]);
        $.ajax({
            url: SERVERURL + 'Usuarios/guardar_imagen_favicon', // Cambia esta ruta por la ruta correcta a tu controlador
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                response = JSON.parse(response);
                if (response.status == 500) {
                    toastr.error("LA IMAGEN NO SE AGREGRO CORRECTAMENTE", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center"
                    });
                } else if (response.status == 200) {
                    toastr.success("IMAGEN AGREGADA CORRECTAMENTE", "NOTIFICACIÓN", {
                        positionClass: "toast-bottom-center",
                    });
                    $('#imagen_productoModal').modal('hide');
                    reloadDataTableProductos();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error al guardar la imagen: ' + textStatus);
            }
        });
    });


    $(document).ready(function() {
        cargarInfoTienda_inicial();
    });

    function cargarInfoTienda_inicial() {
        $.ajax({
            url: SERVERURL + "Usuarios/obtener_infoTiendaOnline",
            type: "GET",
            dataType: "json",
            success: function(response) {
                console.log(response); // Para verificar la respuesta
                if (response && response.length > 0) {
                    $("#nombre_tienda").val(response[0].nombre_tienda);
                    $("#nombre_tienda").attr('value', response[0].nombre_tienda);

                    $("#texto_cabecera").val(response[0].texto_cabecera);
                    $("#texto_footer").val(response[0].texto_footer);
                    $("#texto_precio").val(response[0].texto_precio);
                    $("#color").val(response[0].color);
                    $("#color_botones").val(response[0].color_botones);
                    $("#texto_boton1").val(response[0].texto_boton);
                    alert(response[0].cedula_facturacion);
                    $("#ruc").val(response[0].cedula_facturacion);

                    if (response[0].tienda_creada == 1) {
                        $("#nombre_tienda").prop("readonly", true);
                        $("#tienda-creada").html('<a href="' + response[0].url_imporsuit + '" target="_blank">Ver mi tienda</a>');
                        $("#crear_tienda").css('display', 'none');
                        $("#seccion_nosePermiteTMP").hide();
                    }

                    $("#whatsapp").val(response[0].whatsapp);
                    $("#email").val(response[0].email);
                    $("#direccion").val(response[0].direccion);
                    $('#imagen_logo').attr('src', SERVERURL + response[0].logo_url);

                    $("#instagram").val(response[0].instagram);
                    $("#tiktok").val(response[0].tiktok);
                    $("#facebook").val(response[0].facebook);

                    // Mover la lógica de verificación aquí
                    verificarNombreTienda(response[0].nombre_tienda);
                } else {
                    console.error("La respuesta no contiene datos.");
                }
            },
            error: function(error) {
                console.error("Error al obtener la lista de bodegas:", error);
            },
        });
    }

    function verificarNombreTienda(nombreTienda) {

        if (nombreTienda.includes("TMP_") || nombreTienda.includes("tmp_")) {
            $("#seccion_nosePermiteTMP").show();
            $("#seccion_creacionTienda").hide();
        } else {
            $("#seccion_nosePermiteTMP").hide();
            $("#seccion_creacionTienda").show();
        }
    }


    function cambiarcolor(campo, valor) {

        const formData = new FormData();
        formData.append("campo", campo);
        formData.append("valor", valor);


        $.ajax({
            type: "POST",
            url: "" + SERVERURL + "Usuarios/cambiarcolor",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response2) {
                response2 = JSON.parse(response2);
                console.log(response2);
                console.log(response2[0]);
                if (response2.status == 200) {
                    Swal.fire({
                        icon: "error",
                        title: 'Exito',
                        text: 'Color cambiado correctamente',
                    });
                } else if (response2.status == 200) {
                    Swal.fire({
                        icon: "error",
                        title: response2.title,
                        text: response2.message,
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
                alert("Hubo un problema al agregar el producto temporalmente");
            },
        });

    }
</script>
<script src="<?php echo SERVERURL ?>/Views/Usuarios/js/tienda_online.js"></script>
<?php require_once './Views/templates/footer.php'; ?>