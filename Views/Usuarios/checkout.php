<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Usuarios/css/checkout_style.php'; ?>

<div class="custom-container-fluid mt-4">
    <div class="container d-flex flex-row">
        <div class="left-column">
            <div class="container mt-5">

                <!-- Lista de componentes del formulario -->
                <div class="list-group">
                    <!-- Elemento del formulario -->
                    <!-- TÍTULO DEL FORMULARIO -->
                    <div class="list-group-item" id="tituloFormulario">
                        <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
                            <div>
                                TÍTULO DEL FORMULARIO
                            </div>
                            <div>
                                <span>
                                    <button class="btn btn-secondary btn-sm edit-btn"><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn btn-secondary btn-sm move-up"><i class="fas fa-arrow-up"></i></button>
                                    <button class="btn btn-secondary btn-sm move-down"><i class="fas fa-arrow-down"></i></button>
                                </span>
                            </div>
                        </div>
                        <!-- Sección oculta que se mostrará al hacer clic en editar -->
                        <div class="edit-section hidden">
                            <form>
                                <div class="form-group">
                                    <label for="texto_titulo">Texto</label>
                                    <input type="text" class="form-control" id="texto_titulo" placeholder="">
                                </div>
                                <!-- <div class="form-group">
                                            <label for="inputPassword3" class="col-sm-2 col-form-label">Alineacion</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="alineacion_titulo" id="alineacion_titulo">
                                                    <option value="1">Izquierda </option>
                                                    <option value="2">Centro </option>
                                                    <option value="3">Derecha </option>
                                                </select>
                                            </div>
                                        </div> -->
                                <div class="form-group">
                                    <label for="colorTxt_titulo">Color texto titulo</label>
                                    <input class="colores input-change" type="color" id="colorTxt_titulo" name="colorTxt_titulo" value="">
                                </div>
                                <!-- Añade más campos según sea necesario -->
                            </form>
                        </div>
                    </div>
                    <!-- Fin TÍTULO DEL FORMULARIO -->

                    <!-- Resumen Total... -->
                    <div class="list-group-item" id="resumenTotal" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
                            <div>
                                <button class="btn btn-secondary btn-sm toggle-visibility"><i class="fas fa-eye"></i></button>
                                RESUMEN TOTAL
                            </div>
                            <div>
                                <span>
                                    <button class="btn btn-secondary btn-sm edit-btn"><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn btn-secondary btn-sm move-up"><i class="fas fa-arrow-up"></i></button>
                                    <button class="btn btn-secondary btn-sm move-down"><i class="fas fa-arrow-down"></i></button>
                                </span>
                            </div>
                        </div>

                        <div class="edit-section hidden">
                            <form>
                                <div class="form-group">
                                    <label for="subtotal">Texto subtotal</label>
                                    <input type="text" class="form-control" id="subtotal" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="envio">Texto envío</label>
                                    <input type="text" class="form-control" id="envio" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="total">Texto total</label>
                                    <input type="text" class="form-control" id="total" placeholder="">
                                </div>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="impuestos">
                                    <label class="form-check-label" for="impuestos">Mostrar mensaje adicional sobre impuestos</label>
                                </div>

                            </form>

                        </div>
                    </div>
                    <!-- Fin Resumen Total... -->
                    <!-- TARIFAS DE ENVIO. -->
                    <div class="list-group-item" id="tarifasEnvio">
                        <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
                            <div>
                                <button class="btn btn-secondary btn-sm toggle-visibility"><i class="fas fa-eye"></i></button>
                                TARIFAS DE ENVIO
                            </div>
                            <div>
                                <span>
                                    <button class="btn btn-secondary btn-sm edit-btn"><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn btn-secondary btn-sm move-up"><i class="fas fa-arrow-up"></i></button>
                                    <button class="btn btn-secondary btn-sm move-down"><i class="fas fa-arrow-down"></i></button>
                                </span>
                            </div>
                        </div>
                        <!-- Sección oculta que se mostrará al hacer clic en editar -->
                        <div class="edit-section hidden">
                            <form>
                                <div class="form-group">
                                    <label for="titulo_tarifa">Título</label>
                                    <input type="text" class="form-control" id="titulo_tarifa" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="envio">Gratis</label>
                                    <input type="text" class="form-control" id="gratis" placeholder="">
                                </div>
                                <!-- Añade más campos según sea necesario -->
                            </form>
                        </div>
                    </div>
                    <!-- Fin TARIFAS DE ENVIO -->
                    <!-- CODIGOS DE DESCUENTO -->
                    <div class="list-group-item" id="codigosDescuento_temporal">
                        <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
                            <div>
                                <button class="btn btn-secondary btn-sm toggle-visibility" disabled><i class="fas fa-eye"></i></button>
                                CODIGOS DE DESCUENTO
                            </div>
                            <div>
                                <span>
                                    <button class="btn btn-secondary btn-sm edit-btn" disabled><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn btn-secondary btn-sm move-up" disabled><i class="fas fa-arrow-up"></i></button>
                                    <button class="btn btn-secondary btn-sm move-down" disabled><i class="fas fa-arrow-down"></i></button>
                                </span>
                            </div>
                        </div>

                        <div class="edit-section hidden">
                            <form>
                                <div class="form-group">
                                    <label for="descuentos">Texto de línea de descuentos</label>
                                    <input type="text" class="form-control" id="descuentos" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="etiqueta_descuento">Etiqueta de campo de Código de descuento</label>
                                    <input type="text" class="form-control" id="etiqueta_descuento" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="textoBtn_aplicar">Texto del botón Aplicar</label>
                                    <input type="text" class="form-control" id="textoBtn_aplicar" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="colorBtn_aplicar">Color boton aplicar</label>
                                    <input class="colores input-change" type="color" id="colorBtn_aplicar" name="colorBtn_aplicar" value="">
                                </div>

                            </form>
                        </div>
                    </div>
                    <!-- Fin CODIGOS DE DESCUENTO -->
                    <!-- NOMBRES Y APELLIDOS -->
                    <div class="list-group-item" id="nombresApellidos">
                        <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
                            <div>
                                <button class="btn btn-secondary btn-sm toggle-visibility"><i class="fas fa-eye"></i></button>
                                NOMBRES Y APELLIDOS
                            </div>
                            <div>
                                <span>
                                    <button class="btn btn-secondary btn-sm edit-btn"><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn btn-secondary btn-sm move-up"><i class="fas fa-arrow-up"></i></button>
                                    <button class="btn btn-secondary btn-sm move-down"><i class="fas fa-arrow-down"></i></button>
                                </span>
                            </div>
                        </div>
                        <!-- Sección oculta que se mostrará al hacer clic en editar -->
                        <div class="edit-section hidden">
                            <form>
                                <div class="form-group">
                                    <label for="txt_nombresApellidos">Texto Interno</label>
                                    <input type="text" class="form-control" id="txt_nombresApellidos" placeholder="">
                                </div>
                                <!-- 
                                        <div class="form-check mt-3">
                                            <input class="form-check-input" type="checkbox" value="" id="mostrarIcon_nombresApellidos" checked>
                                            <label class="form-check-label" for="mostrarIcon_nombresApellidos">
                                                Mostrar ícono de campo
                                            </label>
                                        </div>
                                        -->
                                <div class="btn-group" id="icono_nombresApellidos">
                                    <button class="btn btn-secondary icon-btn active" data-value="bxs-user"><i class='bx bxs-user'></i></button>
                                    <button class="btn btn-secondary icon-btn" data-value="bx-user"><i class='bx bx-user'></i></button>
                                    <button class="btn btn-secondary icon-btn" data-value="bxs-user-detail"><i class='bx bxs-user-detail'></i></button>
                                </div>

                                <!-- Añade más campos según sea necesario -->
                            </form>
                        </div>
                    </div>
                    <!-- Fin NOMBRES Y APELLIDOS -->
                    <!-- TELÉFONO -->
                    <div class="list-group-item" id="telefono">
                        <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
                            <div>
                                <button class="btn btn-secondary btn-sm toggle-visibility"><i class="fas fa-eye"></i></button>
                                TELÉFONO
                            </div>
                            <div>
                                <span>
                                    <button class="btn btn-secondary btn-sm edit-btn"><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn btn-secondary btn-sm move-up"><i class="fas fa-arrow-up"></i></button>
                                    <button class="btn btn-secondary btn-sm move-down"><i class="fas fa-arrow-down"></i></button>
                                </span>
                            </div>
                        </div>
                        <!-- Sección oculta que se mostrará al hacer clic en editar -->
                        <div class="edit-section hidden">
                            <form>
                                <div class="form-group">
                                    <label for="txt_telefono">Texto Interno</label>
                                    <input type="text" class="form-control" id="txt_telefono" placeholder="">
                                </div>

                                <div class="btn-group" id="icono_telefono">
                                    <button class="btn btn-secondary icon-btn active" data-value="bxs-user"><i class='bx bxs-phone-call'></i></button>
                                    <button class="btn btn-secondary icon-btn" data-value="bx-user"><i class='bx bxl-whatsapp'></i></button>
                                    <button class="btn btn-secondary icon-btn" data-value="bxs-user-detail"><i class='bx bx-phone-call'></i></button>
                                </div>

                                <!-- Añade más campos según sea necesario -->
                            </form>
                        </div>
                    </div>
                    <!-- Fin TELÉFONO -->
                    <!-- CALLE PRINCIPAL -->
                    <div class="list-group-item" id="calle_principal">
                        <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
                            <div>
                                <button class="btn btn-secondary btn-sm toggle-visibility"><i class="fas fa-eye"></i></button>
                                CALLE PRINCIPAL
                            </div>
                            <div>
                                <span>
                                    <button class="btn btn-secondary btn-sm edit-btn"><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn btn-secondary btn-sm move-up"><i class="fas fa-arrow-up"></i></button>
                                    <button class="btn btn-secondary btn-sm move-down"><i class="fas fa-arrow-down"></i></button>
                                </span>
                            </div>
                        </div>
                        <!-- Sección oculta que se mostrará al hacer clic en editar -->
                        <div class="edit-section hidden">
                            <form>
                                <div class="form-group">
                                    <label for="titulo_calle_principal">Titulo</label>
                                    <input type="text" class="form-control" id="titulo_calle_principal" placeholder="">
                                </div>

                                <div class="form-group">
                                    <label for="txt_calle_principal">Texto Interno</label>
                                    <input type="text" class="form-control" id="txt_calle_principal" placeholder="">
                                </div>

                            </form>
                        </div>
                    </div>
                    <!-- Fin CALLE PRINCIPAL -->
                    <!-- CALLE secundaria -->
                    <div class="list-group-item" id="calle_secundaria">
                        <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
                            <div>
                                <button class="btn btn-secondary btn-sm toggle-visibility"><i class="fas fa-eye"></i></button>
                                CALLE SEGUNDARIA
                            </div>
                            <div>
                                <span>
                                    <button class="btn btn-secondary btn-sm edit-btn"><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn btn-secondary btn-sm move-up"><i class="fas fa-arrow-up"></i></button>
                                    <button class="btn btn-secondary btn-sm move-down"><i class="fas fa-arrow-down"></i></button>
                                </span>
                            </div>
                        </div>
                        <!-- Sección oculta que se mostrará al hacer clic en editar -->
                        <div class="edit-section hidden">
                            <form>
                                <div class="form-group">
                                    <label for="titulo_calle_secundaria">Titulo</label>
                                    <input type="text" class="form-control" id="titulo_calle_secundaria" placeholder="">
                                </div>

                                <div class="form-group">
                                    <label for="txt_calle_secundaria">Texto Interno</label>
                                    <input type="text" class="form-control" id="txt_calle_secundaria" placeholder="">
                                </div>

                            </form>
                        </div>
                    </div>
                    <!-- Fin CALLE secundaria -->
                    <!-- BARRIO O REFERENCIA -->
                    <div class="list-group-item" id="barrio_referencia">
                        <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
                            <div>
                                <button class="btn btn-secondary btn-sm toggle-visibility"><i class="fas fa-eye"></i></button>
                                BARRIO O REFERENCIA
                            </div>
                            <div>
                                <span>
                                    <button class="btn btn-secondary btn-sm edit-btn"><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn btn-secondary btn-sm move-up"><i class="fas fa-arrow-up"></i></button>
                                    <button class="btn btn-secondary btn-sm move-down"><i class="fas fa-arrow-down"></i></button>
                                </span>
                            </div>
                        </div>
                        <!-- Sección oculta que se mostrará al hacer clic en editar -->
                        <div class="edit-section hidden">
                            <form>
                                <div class="form-group">
                                    <label for="titulo_barrio_referencia">Titulo</label>
                                    <input type="text" class="form-control" id="titulo_barrio_referencia" placeholder="">
                                </div>

                                <div class="form-group">
                                    <label for="txt_barrio_referencia">Texto Interno</label>
                                    <input type="text" class="form-control" id="txt_barrio_referencia" placeholder="">
                                </div>

                            </form>
                        </div>
                    </div>
                    <!-- Fin BARRIO O REFERENCIA -->
                    <!-- provincia -->
                    <div class="list-group-item" id="provincia">
                        <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
                            <div>
                                <button class="btn btn-secondary btn-sm toggle-visibility"><i class="fas fa-eye"></i></button>
                                PROVINCIA
                            </div>
                            <div>
                                <span>
                                    <button class="btn btn-secondary btn-sm edit-btn"><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn btn-secondary btn-sm move-up"><i class="fas fa-arrow-up"></i></button>
                                    <button class="btn btn-secondary btn-sm move-down"><i class="fas fa-arrow-down"></i></button>
                                </span>
                            </div>
                        </div>
                        <!-- Sección oculta que se mostrará al hacer clic en editar -->
                        <div class="edit-section hidden">
                            <form>
                                <div class="form-group">
                                    <label for="titulo_provincia">Titulo</label>
                                    <input type="text" class="form-control" id="titulo_provincia" placeholder="">
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Fin provincia -->
                    <!-- ciudad -->
                    <div class="list-group-item" id="ciudad">
                        <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
                            <div>
                                <button class="btn btn-secondary btn-sm toggle-visibility"><i class="fas fa-eye"></i></button>
                                CIUDAD
                            </div>
                            <div>
                                <span>
                                    <button class="btn btn-secondary btn-sm edit-btn"><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn btn-secondary btn-sm move-up"><i class="fas fa-arrow-up"></i></button>
                                    <button class="btn btn-secondary btn-sm move-down"><i class="fas fa-arrow-down"></i></button>
                                </span>
                            </div>
                        </div>
                        <!-- Sección oculta que se mostrará al hacer clic en editar -->
                        <div class="edit-section hidden">
                            <form>
                                <div class="form-group">
                                    <label for="titulo_ciudad">Titulo</label>
                                    <input type="text" class="form-control" id="titulo_ciudad" placeholder="">
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Fin ciudad -->
                    <!-- Comentario -->
                    <div class="list-group-item" id="comentario">
                        <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
                            <div>
                                <button class="btn btn-secondary btn-sm toggle-visibility"><i class="fas fa-eye"></i></button>
                                COMENTARIO
                            </div>
                            <div>
                                <span>
                                    <button class="btn btn-secondary btn-sm edit-btn"><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn btn-secondary btn-sm move-up"><i class="fas fa-arrow-up"></i></button>
                                    <button class="btn btn-secondary btn-sm move-down"><i class="fas fa-arrow-down"></i></button>
                                </span>
                            </div>
                        </div>
                        <!-- Sección oculta que se mostrará al hacer clic en editar -->
                        <div class="edit-section hidden">
                            <form>
                                <div class="form-group">
                                    <label for="titulo_comentario">Titulo</label>
                                    <input type="text" class="form-control" id="titulo_comentario" placeholder="">
                                </div>

                                <div class="form-group">
                                    <label for="txt_comentario">Texto Interno</label>
                                    <input type="text" class="form-control" id="txt_comentario" placeholder="">
                                </div>

                            </form>
                        </div>
                    </div>
                    <!-- Fin Comentario -->
                    <!-- BOTON DE COMPRA -->
                    <div class="list-group-item" id="btn_comprar">
                        <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
                            <div>
                                <button class="btn btn-secondary btn-sm toggle-visibility"><i class="fas fa-eye"></i></button>
                                BOTON DE COMPRA
                            </div>
                            <div>
                                <span>
                                    <button class="btn btn-secondary btn-sm edit-btn"><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn btn-secondary btn-sm move-up"><i class="fas fa-arrow-up"></i></button>
                                    <button class="btn btn-secondary btn-sm move-down"><i class="fas fa-arrow-down"></i></button>
                                </span>
                            </div>
                        </div>
                        <!-- Sección oculta que se mostrará al hacer clic en editar -->
                        <div class="edit-section hidden">
                            <form>
                                <div class="form-group">
                                    <label for="textoBtn_comprar">Texto del botón comprar</label>
                                    <input type="text" class="form-control" id="textoBtn_comprar" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="colorBtn_comprar">Color boton comprar</label>
                                    <input class="colores input-change" type="color" id="colorBtn_comprar" name="colorBtn_comprar" value="">
                                </div>
                                <!-- Añade más campos según sea necesario -->
                                <div class="form-group">
                                    <label for="animacionBtn_comprar">Animación del botón</label>
                                    <select class="form-control" id="animacionBtn_comprar">
                                        <option value="">Ninguna</option>
                                        <option value="bounce">Bounce</option>
                                        <option value="shake">Shake</option>
                                        <option value="pulse">Pulse</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Fin BOTON DE COMPRA -->
                    <!-- oferta_adicional -->
                    <!-- <div class="list-group-item" id="oferta_adicional">
                                <div class="d-flex justify-content-between align-items-center" style="width: 100%;">
                                    <div>
                                        <button class="btn btn-secondary btn-sm toggle-visibility"><i class="fas fa-eye"></i></button>
                                        Oferta Adicional
                                    </div>
                                    <div>
                                        <span>
                                            <button class="btn btn-secondary btn-sm edit-btn"><i class="fas fa-pencil-alt"></i></button>
                                            <button class="btn btn-secondary btn-sm move-up"><i class="fas fa-arrow-up"></i></button>
                                            <button class="btn btn-secondary btn-sm move-down"><i class="fas fa-arrow-down"></i></button>
                                        </span>
                                    </div>
                                </div>
                               
                                <div class="edit-section hidden">
                                    <form>
                                        <div class="form-group">
                                            <label for="txt_oferta_adicional">Comentario de ofecta adicional</label>
                                            <input type="text" class="form-control" id="txt_oferta_adicional" placeholder="">
                                        </div>

                                        <div class="form-group">
                                            <label for="valor_oferta_adicional">Valor de oferta</label>
                                            <input type="text" class="form-control" id="valor_oferta_adicional" placeholder="">
                                        </div>

                                    </form>
                                </div>
                            </div> -->
                    <!-- Fin oferta_adicional -->
                </div>
            </div>
        </div>

        <div class="right-column">
            <div class="col-md-8 caja" style="background-color: white;">
                <div id="previewContainer" class="p-3">

                    <div id="tituloFormularioPreview">
                        <h4 id="texto_tituloPreview">PAGA AL RECIBIR EN CASA!</h4>
                    </div>
                    <div id="" class="caja_variable">
                        <div class="d-flex flex-row">
                            <p id="">Subtotal</p>
                            <span style="width: 100%; text-align: end;">$19.99</span>
                        </div>

                        <hr />
                        <div class="d-flex flex-row">
                            <p id="">Total</p>
                            <span style="width: 100%; text-align: end;">$19.99</span>
                        </div>
                    </div>
                    <div id="tarifasEnvioPreview">
                        <hr />
                        <p id="titulo_tarifaPreview" style="font-weight:bold;">Método de envío</p>
                        <div class="caja_transparente d-flex flex-row">
                            <!-- <input type="radio" name="metodoEnvio" checked> -->
                            <label for="envioGratisPreview"> Envío gratis</label>
                            <label id="gratisPreview" style="width: 60%; text-align: end; font-weight:bold;">Gratis</label>
                        </div>
                        <hr />
                    </div>
                    <!-- código de descuento -->
                    <div class="discount-code-container" id="codigosDescuentoPreview">

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Código de descuento" id="etiqueta_descuentoPreview" aria-label="Código de descuento">
                            <div class="input-group-append">
                                <button class="btn btn-dark" id="textoBtn_aplicarPreview" type="button">Aplicar</button>
                            </div>
                        </div>


                        <div class="applied-discount">
                            <span class="discount-tag">4SALE $4.00</span>
                        </div>
                    </div>
                    <!-- Fin código de descuento -->
                    <!-- Nombre y apellidos -->
                    <div class="form-group" id="nombresApellidosPreview" style="position: relative; padding-top: 5px;">

                        <label class="sub_titulos">Nombres y Apellidos</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="icono_nombresApellidosPreview"><i class='bx bxs-user'></i></span>
                            </div>
                            <input type="text" class="form-control" id="txt_nombresApellidosPreview" placeholder="Nombre y Apellido">
                        </div>
                    </div>
                    <!-- Fin Nombre y apellidos -->
                    <!-- Telefono -->
                    <div class="form-group" id="telefonoPreview" style="position: relative; padding-top: 3px;">
                        <label class="sub_titulos">Teléfono</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="icono_telefonoPreview"><i class='bx bxs-phone-call'></i></span>
                            </div>
                            <input type="text" class="form-control" id="txt_telefonoPreview" placeholder="Teléfono">
                        </div>
                    </div>
                    <!-- Fin Telefono -->
                    <!-- calle_principal -->
                    <div class="form-group" id="calle_principalPreview" style="position: relative; padding-top: 3px;">
                        <label class="sub_titulos" id="titulo_calle_principalPreview">Calle Principal</label>
                        <div class="">
                            <input type="text" class="form-control" id="txt_calle_principalPreview" placeholder="">
                        </div>
                    </div>
                    <!-- Fin calle_principal -->
                    <!-- calle_secundaria -->
                    <div class="form-group" id="calle_secundariaPreview" style="position: relative; padding-top: 3px;">
                        <label class="sub_titulos" id="titulo_calle_secundariaPreview">Calle Secundaria</label>
                        <div class="">
                            <input type="text" class="form-control" id="txt_calle_secundariaPreview" placeholder="">
                        </div>
                    </div>
                    <!-- Fin calle_secundaria -->
                    <!-- barrio_referencia -->
                    <div class="form-group" id="barrio_referenciaPreview" style="position: relative; padding-top: 3px;">
                        <label class="sub_titulos" id="titulo_barrio_referenciaPreview">Barrio o Referencia</label>
                        <div class="">
                            <input type="text" class="form-control" id="txt_barrio_referenciaPreview" placeholder="">
                        </div>
                    </div>
                    <!-- Fin barrio_referencia -->
                    <!-- provincia -->
                    <div class="form-group" id="provinciaPreview" style="position: relative; padding-top: 3px;">
                        <label class="sub_titulos" id="titulo_provinciaPreview">Provincia</label>
                        <div class="">
                            <select class="datos form-control " onchange="cargar_provincia_pedido()" id="provinica" name="provinica" required>
                                <option value="">Provincia *</option>
                                
                            </select>
                        </div>
                    </div>
                    <!-- Fin provincia -->
                    <!-- ciudad -->
                    <div class="form-group" id="ciudadPreview" style="position: relative; padding-top: 3px;">
                        <label class="sub_titulos" id="titulo_ciudadPreview">Ciudad</label>
                        <div>
                            <div id="div_ciudad" onclick="verify()">
                                <select class="datos form-control" id="ciudad_entrega" name="ciudad_entrega" onchange="seleccionarProvincia()" required disabled>
                                    <option value="">Ciudad *</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Fin ciudad -->
                    <!-- comentario -->
                    <div class="form-group" id="comentarioPreview" style="position: relative; padding-top: 3px;">
                        <label class="sub_titulos" id="titulo_comentarioPreview">Barrio o Referencia</label>
                        <div class="">
                            <input type="text" class="form-control" id="txt_comentarioPreview" placeholder="">
                        </div>
                    </div>
                    <!-- Fin comentario -->
                    <!-- Boton Comprar -->
                    <div id="btn_comprarPreview" style="padding-top: 20px;">

                        <div class="input-group mb-3 d-flex justify-content-center">

                            <button class="btn_comprar btn-dark" id="textoBtn_comprarPreview" type="button">COMPRAR AHORA</button>

                        </div>

                    </div>
                    <!-- Fin Boton Comprar -->
                    <!-- oferta_adicional -->
                    <div class="form-group caja_oferta" id="oferta_adicionalPreview" style="position: relative; padding-top: 3px;">
                        <label class="sub_titulos" id="">Titulo de oferta - </label>
                        <label class="sub_titulos" id="">Precio de oferta</label>
                    </div>
                    <!-- Fin oferta_adicional -->
                </div>
            </div>
        </div>
        <!-- end container -->
        <div class="save-button-container">
            <button id="saveFormState" class="btn btn-success">Guardar Cambios</button>
        </div>
    </div>
</div>
<?php require_once './Views/templates/footer.php'; ?>