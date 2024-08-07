<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Usuarios/css/tiendaOnline_style.php'; ?>

<?php require_once './Views/Usuarios/Modales/agregar_banner.php'; ?>
<?php require_once './Views/Usuarios/Modales/editar_banner.php'; ?>
<?php require_once './Views/Usuarios/Modales/agregar_testimonio.php'; ?>
<?php require_once './Views/Usuarios/Modales/editar_testimonio.php'; ?>
<?php require_once './Views/Usuarios/Modales/agregar_horizontal.php'; ?>
<?php require_once './Views/Usuarios/Modales/editar_horizontal.php'; ?>
<?php require_once './Views/Usuarios/Modales/editar_icono.php'; ?>
<?php require_once './Views/Usuarios/Modales/agregar_dominio.php'; ?>

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
                                    <form id="imageFormFavicon" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <input type="file" class="form-control" id="imageInputFav" accept=".jpg,.jpeg,.png" name="imagen">
                                        </div>
                                        <img id="imagePreviewFav" class="image-preview mb-3" src="" alt="Preview" width="50%">
                                    </form>

                                    <div class="justify-content-between align-items-center mb-3">
                                        <div class="d-flex">
                                            <button class="btn btn-success" onclick="abrir_agregar_dominio()"><i class="fas fa-plus"></i> Agregar Dominio Propio</button>
                                        </div>
                                    </div>
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
                                        <div id="seccion_creacionTienda" style="display: none;">
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
                                        <input type="text" class="form-control cambio" id="whatsapp" name="whatsapp" placeholder="Ejemplo:0999999999">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="email_tienda">Email:</label>
                                        <input type="email" class="form-control cambio" id="email" name="email">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="direccion_tienda">Dirección:</label>
                                        <input type="text" class="form-control cambio" id="direccion_tienda" name="direccion_tienda">
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
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <!-- <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" value="" id="habilitarTextoFlotante" checked>
                                        <label class="form-check-label" for="habilitarTextoFlotante">
                                            Habilitar texto flotante
                                        </label>
                                    </div> -->

                                    <div class="justify-content-between align-items-center mb-3">
                                        <div class="d-flex">
                                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregar_horizontalModal"><i class="fas fa-plus"></i> Agregar</button>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <!-- <table class="table table-bordered table-striped table-hover"> -->
                                        <table id="datatable_horizonal" width="100%" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-nowrap">Texto</th>
                                                    <th class="text-nowrap">Posicion</th>
                                                    <th class="text-nowrap">Visible</th>
                                                    <th class="text-nowrap">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tableBody_horizonal"></tbody>
                                        </table>
                                    </div>

                                    <!-- <div class="form-check mb-3">
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
                                    </div> -->
                                </div>
                            </div>
                        </div>
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
                                    <th class="text-nowrap">Imagen</th>
                                    <th class="text-nowrap">Titulo</th>
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
                                    <th class="text-nowrap">Enlace</th>
                                    <th class="text-nowrap">Acción</th>
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

<script src="<?php echo SERVERURL ?>Views/Usuarios/js/tienda_online.js"></script>
<?php require_once './Views/templates/footer.php'; ?>