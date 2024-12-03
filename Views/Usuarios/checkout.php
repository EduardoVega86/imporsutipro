<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Usuarios/css/checkout_style.php'; ?>

<div class="custom-container-fluid mt-4">
    <div class="container seccion_principal">
        <div class="left-column">
            <div class="container mt-5">

                <!-- Lista de componentes del formulario -->
                <div class="list-group">
                    <!-- Elemento del formulario -->
                    <!-- TÍTULO DEL FORMULARIO -->
                    <div class="list-group-item" id="tituloFormulario">
                        <div class="justify-content-between align-items-center editor_visual" style="width: 100%;">
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
                        <div class="justify-content-between align-items-center editor_visual" style="width: 100%;">
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
                        <div class="justify-content-between align-items-center editor_visual" style="width: 100%;">
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
                        <div class="justify-content-between align-items-center editor_visual" style="width: 100%;">
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
                        <div class="justify-content-between align-items-center editor_visual" style="width: 100%;">
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
                        <div class="justify-content-between align-items-center editor_visual" style="width: 100%;">
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
                        <div class="justify-content-between align-items-center editor_visual" style="width: 100%;">
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

                                <div class="btn-group" id="icono_calle_principal">
                                    <button class="btn btn-secondary icon-btn active" data-value="bxs-user"><i class='bx bx-map'></i></button>
                                    <button class="btn btn-secondary icon-btn" data-value="bx-user"><i class='bx bxs-map-pin'></i></button>
                                    <button class="btn btn-secondary icon-btn" data-value="bxs-user-detail"><i class='bx bx-map-alt'></i></button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <!-- Fin CALLE PRINCIPAL -->
                    <!-- CALLE secundaria -->
                    <div class="list-group-item" id="calle_secundaria">
                        <div class="justify-content-between align-items-center editor_visual" style="width: 100%;">
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

                                <div class="btn-group" id="icono_calle_secundaria">
                                    <button class="btn btn-secondary icon-btn active" data-value="bxs-user"><i class='bx bx-map'></i></button>
                                    <button class="btn btn-secondary icon-btn" data-value="bx-user"><i class='bx bxs-map-pin'></i></button>
                                    <button class="btn btn-secondary icon-btn" data-value="bxs-user-detail"><i class='bx bx-map-alt'></i></button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <!-- Fin CALLE secundaria -->
                    <!-- BARRIO O REFERENCIA -->
                    <div class="list-group-item" id="barrio_referencia">
                        <div class="justify-content-between align-items-center editor_visual" style="width: 100%;">
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

                                <div class="btn-group" id="icono_barrio_referencia">
                                    <button class="btn btn-secondary icon-btn active" data-value="bxs-user"><i class='bx bx-map'></i></button>
                                    <button class="btn btn-secondary icon-btn" data-value="bx-user"><i class='bx bxs-map-pin'></i></button>
                                    <button class="btn btn-secondary icon-btn" data-value="bxs-user-detail"><i class='bx bx-street-view'></i></button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <!-- Fin BARRIO O REFERENCIA -->
                    <!-- provincia -->
                    <div class="list-group-item" id="provincia">
                        <div class="justify-content-between align-items-center editor_visual" style="width: 100%;">
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
                        <div class="justify-content-between align-items-center editor_visual" style="width: 100%;">
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
                        <div class="justify-content-between align-items-center editor_visual" style="width: 100%;">
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

                                <div class="btn-group" id="icono_comentario">
                                    <button class="btn btn-secondary icon-btn active" data-value="bxs-user"><i class='bx bx-message-dots'></i></button>
                                    <button class="btn btn-secondary icon-btn" data-value="bx-user"><i class='bx bx-comment-detail'></i></button>
                                    <button class="btn btn-secondary icon-btn" data-value="bxs-user-detail"><i class='bx bxs-message-rounded-dots'></i></button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <!-- Fin Comentario -->
                    <!-- BOTON DE COMPRA -->
                    <div class="list-group-item" id="btn_comprar">
                        <div class="justify-content-between align-items-center editor_visual" style="width: 100%;">
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
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="icono_calle_principalPreview"><i class='bx bx-map'></i></span>
                            </div>
                            <input type="text" class="form-control" id="txt_calle_principalPreview" placeholder="">
                        </div>
                    </div>
                    <!-- Fin calle_principal -->
                    <!-- calle_secundaria -->
                    <div class="form-group" id="calle_secundariaPreview" style="position: relative; padding-top: 3px;">
                        <label class="sub_titulos" id="titulo_calle_secundariaPreview">Calle Secundaria</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="icono_calle_secundariaPreview"><i class='bx bx-map'></i></span>
                            </div>
                            <input type="text" class="form-control" id="txt_calle_secundariaPreview" placeholder="">
                        </div>
                    </div>
                    <!-- Fin calle_secundaria -->
                    <!-- barrio_referencia -->
                    <div class="form-group" id="barrio_referenciaPreview" style="position: relative; padding-top: 3px;">
                        <label class="sub_titulos" id="titulo_barrio_referenciaPreview">Barrio o Referencia</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="icono_barrio_referenciaPreview"><i class='bx bx-map'></i></span>
                            </div>
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
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="icono_comentarioPreview"><i class='bx bx-message-dots'></i></span>
                            </div>
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
<script>
    $(document).ready(function() {
        $(".UpperCase").on("keypress", function() {
            $input = $(this);
            setTimeout(function() {
                $input.val($input.val().toUpperCase());
            }, 50);
        })
    })


    // Espera a que el documento esté listo
    $(document).ready(function() {
        // Maneja el evento clic del botón de editar
        $('.edit-btn').click(function() {
            // Encuentra la sección de edición más cercana y alterna la clase 'hidden'
            $(this).closest('.list-group-item').find('.edit-section').toggleClass('hidden');
        });
    });

    // dejar de visualizar o no un codigo
    $(document).ready(function() {
        $('.toggle-visibility').click(function() {
            var listItem = $(this).closest('.list-group-item');
            var listItemID = listItem.attr('id');
            var previewItem = $('#previewContainer').find('#' + listItemID + 'Preview');

            // Toggle de visibilidad en la vista previa
            previewItem.toggle();

            // Cambio del estado y actualización en JSON
            var estadoActual = previewItem.is(':visible') ? '1' : '0';
            listItem.data('estado', estadoActual); // Guardar el estado como data attribute

            // Cambiar el ícono dependiendo de la visibilidad
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });
    });

    //Flechas de arriba y abajo
    $(document).ready(function() {
        // Mover elemento hacia arriba
        $('.move-up').click(function() {
            var listItem = $(this).closest('.list-group-item');
            var listItemID = listItem.attr('id'); // Asegúrate de que cada list-group-item tenga un id único.

            // Mueve el elemento en la columna de la izquierda
            listItem.prev().before(listItem);

            // Ahora mueve el elemento correspondiente en la columna de la derecha
            var previewItem = $('#previewContainer').find('#' + listItemID + 'Preview');
            previewItem.prev().before(previewItem);
        });

        // Mover elemento hacia abajo
        $('.move-down').click(function() {
            var listItem = $(this).closest('.list-group-item');
            var listItemID = listItem.attr('id'); // Asegúrate de que cada list-group-item tenga un id único.

            // Mueve el elemento en la columna de la izquierda
            listItem.next().after(listItem);

            // Ahora mueve el elemento correspondiente en la columna de la derecha
            var previewItem = $('#previewContainer').find('#' + listItemID + 'Preview');
            previewItem.next().after(previewItem);
        });

        //cambiar animacion de boton comprar en tiempo real

        $('#animacionBtn_comprar').change(function() {
            var animacionSeleccionada = $(this).val();
            var btnPreview = $('#textoBtn_comprarPreview'); // El ID del botón en el preview

            // Limpiar clases de animación antes de aplicar la nueva
            btnPreview.removeClass('bounce shake pulse');
            if (animacionSeleccionada) {
                btnPreview.addClass(animacionSeleccionada);
            }
        });

        // Evento para cambiar el color del texto del título
        $('#colorTxt_titulo').on('change', function() {
            $('#texto_tituloPreview').css('color', $(this).val());
        });

        // Cambiar el color del botón Aplicar en tiempo real
        $('#colorBtn_aplicar').on('change', function() {
            $('#textoBtn_aplicarPreview').css('background-color', $(this).val());
        });

        // Cambiar el color del botón comprar en tiempo real
        $('#colorBtn_comprar').on('change', function() {
            console.log("Evento change disparado para colorBtn_comprar");
            $('#textoBtn_comprarPreview').css('background-color', $(this).val());
        });
    });
    //PREVIEW
    document.addEventListener('DOMContentLoaded', () => {
        // Asumiendo que tienes un input con id='texto_titulo'
        const tituloInput = document.getElementById('texto_titulo');
        tituloInput.addEventListener('input', function() {
            document.getElementById('texto_tituloPreview').textContent = this.value;
        });

        // Asume que tienes otro input para la descripción con id='subtotal'
        const subtotalInput = document.getElementById('subtotal');
        subtotalInput.addEventListener('input', function() {
            document.getElementById('subtotalPreview').textContent = this.value;
        });

        // Repite el proceso para otros campos de entrada
        const envioInput = document.getElementById('envio');
        envioInput.addEventListener('input', function() {
            document.getElementById('envioPreview').textContent = this.value;
        });

        const totalInput = document.getElementById('total');
        totalInput.addEventListener('input', function() {
            document.getElementById('totalPreview').textContent = this.value;
        });

        const titulo_tarifaInput = document.getElementById('titulo_tarifa');
        titulo_tarifaInput.addEventListener('input', function() {
            document.getElementById('titulo_tarifaPreview').textContent = this.value;
        });

        const gratisInput = document.getElementById('gratis');
        gratisInput.addEventListener('input', function() {
            document.getElementById('gratisPreview').textContent = this.value;
        });

        const descuentosInput = document.getElementById('descuentos');
        descuentosInput.addEventListener('input', function() {
            document.getElementById('descuentosPreview').textContent = this.value;
        });

        // Asegurarse que los elementos están correctamente enlazados con los eventos de actualización
        const etiqueta_descuentoInput = document.getElementById('etiqueta_descuento');
        const textoBtn_aplicarInput = document.getElementById('textoBtn_aplicar');

        etiqueta_descuentoInput.addEventListener('input', function() {
            var previewInput = document.getElementById('etiqueta_descuentoPreview');
            previewInput.placeholder = this.value; // Cambiando el placeholder en lugar de textContent
        });

        textoBtn_aplicarInput.addEventListener('input', function() {
            document.getElementById('textoBtn_aplicarPreview').textContent = this.value;
        });

        const txt_nombresApellidosInput = document.getElementById('txt_nombresApellidos');
        txt_nombresApellidosInput.addEventListener('input', function() {
            var previewInput = document.getElementById('txt_nombresApellidosPreview');
            previewInput.placeholder = this.value; // Cambiando el placeholder en lugar de textContent
        });

        const txt_telefonoInput = document.getElementById('txt_telefono');
        txt_telefonoInput.addEventListener('input', function() {
            var previewInput = document.getElementById('txt_telefonoPreview');
            previewInput.placeholder = this.value; // Cambiando el placeholder en lugar de textContent
        });

        const titulo_calle_principalInput = document.getElementById('titulo_calle_principal');
        titulo_calle_principalInput.addEventListener('input', function() {
            document.getElementById('titulo_calle_principalPreview').textContent = this.value;
        });

        const txt_calle_principalInput = document.getElementById('txt_calle_principal');
        txt_calle_principalInput.addEventListener('input', function() {
            var previewInput = document.getElementById('txt_calle_principalPreview');
            previewInput.placeholder = this.value; // Cambiando el placeholder en lugar de textContent
        });

        const titulo_calle_secundariaInput = document.getElementById('titulo_calle_secundaria');
        titulo_calle_secundariaInput.addEventListener('input', function() {
            document.getElementById('titulo_calle_secundariaPreview').textContent = this.value;
        });

        const txt_calle_secundariaInput = document.getElementById('txt_calle_secundaria');
        txt_calle_secundariaInput.addEventListener('input', function() {
            var previewInput = document.getElementById('txt_calle_secundariaPreview');
            previewInput.placeholder = this.value; // Cambiando el placeholder en lugar de textContent
        });

        const titulo_barrio_referenciaInput = document.getElementById('titulo_barrio_referencia');
        titulo_barrio_referenciaInput.addEventListener('input', function() {
            document.getElementById('titulo_barrio_referenciaPreview').textContent = this.value;
        });

        const txt_barrio_referenciaInput = document.getElementById('txt_barrio_referencia');
        txt_barrio_referenciaInput.addEventListener('input', function() {
            var previewInput = document.getElementById('txt_barrio_referenciaPreview');
            previewInput.placeholder = this.value; // Cambiando el placeholder en lugar de textContent
        });

        const titulo_comentarioInput = document.getElementById('titulo_comentario');
        titulo_comentarioInput.addEventListener('input', function() {
            document.getElementById('titulo_comentarioPreview').textContent = this.value;
        });

        const txt_comentarioInput = document.getElementById('txt_comentario');
        txt_comentarioInput.addEventListener('input', function() {
            var previewInput = document.getElementById('txt_comentarioPreview');
            previewInput.placeholder = this.value; // Cambiando el placeholder en lugar de textContent
        });

        const titulo_provinciaInput = document.getElementById('titulo_provincia');
        titulo_provinciaInput.addEventListener('input', function() {
            document.getElementById('titulo_provinciaPreview').textContent = this.value;
        });

        const titulo_ciudadInput = document.getElementById('titulo_ciudad');
        titulo_ciudadInput.addEventListener('input', function() {
            document.getElementById('titulo_ciudadPreview').textContent = this.value;
        });

        const textoBtn_comprarInput = document.getElementById('textoBtn_comprar');
        textoBtn_comprarInput.addEventListener('input', function() {
            document.getElementById('textoBtn_comprarPreview').textContent = this.value;
        });

    });

    // Funcion para que consuma los datos de checkout.json y los utilice

    document.addEventListener('DOMContentLoaded', function() {
        loadAndSetInitialData();
    });

    function loadAndSetInitialData() {
        id_plataforma = <?php echo $_SESSION["id_plataforma"]; ?>

        $.getJSON(SERVERURL + 'Models/modales/' + id_plataforma + '_modal.json', function(data) {
            data.forEach(item => {
                processItem(item);
            });
        }).fail(handleLoadingError);
    }

    function processItem(item) {
        Object.keys(item.content).forEach(key => {
            updateFieldAndPreview(key, item.content[key], item.id_elemento);
        });
        toggleVisibility(item.estado, item.id_elemento);
        reorderElements(item.id_elemento, item.posicion);
    }

    function updateFieldAndPreview(key, value, id_elemento) {
        const field = $('#' + key);
        const previewField = $('#' + key + 'Preview');

        // Aplicar valor al campo y disparar evento change para asegurar cualquier lógica de UI
        updateFieldValue(field, value);

        // Si el elemento es un input, actualiza el placeholder; si es otro elemento (como label), actualiza su texto
        if (previewField.is('input')) {
            previewField.attr('placeholder', value);
        } else {
            previewField.text(value);
        }
        // Específico para animaciones y colores
        if (key === 'animacionBtn_comprar') {
            const btnPreview = $('#textoBtn_comprarPreview');
            btnPreview.removeClass('bounce shake pulse');
            btnPreview.addClass(value);
        } else if (key.startsWith('color')) {
            // Asegurarse de que se actualice el color directamente en la vista previa adecuadamente
            applyColor(key, value, previewField);
        } else if (key.includes('txt_')) {
            previewField.attr('placeholder', value);
        } else if (key.includes('icono')) {
            previewField.html("<i class='" + value + "'></i>");
        } else {
            previewField.text(value);
        }
    }

    function applyColor(key, value, previewField) {
        if (key === 'colorBtn_comprar') {
            // Aplicar el color directamente al botón de compra en la vista previa
            $('#textoBtn_comprarPreview').css('background-color', value);
        } else if (key === 'colorTxt_titulo') {
            $('#texto_tituloPreview').css('color', value);
        } else {
            // Aplica color general si es necesario a otros elementos
            previewField.css('color', value);
        }
    }

    function updateFieldValue(field, value) {
        if (field.is(':checkbox')) {
            field.prop('checked', value === 'on');
        } else {
            field.val(value).change(); // Trigger change for preview updates
        }
    }

    function updatePreviewField(key, previewField, value) {
        if (!previewField.length) {
            console.warn('No preview field found for', key);
            return;
        }

        if (key.includes('txt_')) {
            previewField.attr('placeholder', value);
        } else if (key.includes('icono')) {
            previewField.html("<i class='" + value + "'></i>");
        } else {
            previewField.text(value);
        }
    }

    function toggleVisibility(state, id_elemento) {
        const preview = $('#' + id_elemento + 'Preview');
        const toggleButton = $('#' + id_elemento).find('.toggle-visibility i');

        // Actualiza la visibilidad del elemento
        if (state === '0') {
            preview.hide();
            toggleButton.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            preview.show();
            toggleButton.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    }

    function reorderElements(id_elemento, position) {
        const element = $('#' + id_elemento);
        const preview = $('#' + id_elemento + 'Preview');
        reorderElement(element, position, '.list-group');
        reorderElement(preview, position, '#previewContainer');
    }

    function reorderElement(element, position, containerSelector) {
        if (element.index() !== position) {
            element.detach();
            position === 0 ? $(containerSelector).prepend(element) : $(containerSelector).children().eq(position - 1).after(element);
        }
    }

    function updateTextAlignment(value) {
        const textAlign = value === '1' ? 'left' : value === '2' ? 'center' : 'right';
        $('#tituloFormularioPreview').css('text-align', textAlign);
    }

    function updateColor(key, value) {
        if (key === 'colorTxt_titulo') {
            $('#texto_tituloPreview').css('color', value);
        } else if (key === 'colorBtn_aplicar') {
            $('#textoBtn_aplicarPreview').css('background-color', value);
        } else if (key === 'colorBtn_comprar') {
            $('#textoBtn_comprarPreview').css('background-color', value);
        }
    }


    function handleLoadingError(jqXHR, textStatus, errorThrown) {
        console.error('Error loading JSON:', textStatus, errorThrown);
    }


    // Funcion para boton guardar

    function saveFormState() {
        var itemList = [];

        var defaultCodigosDescuento = {
            "id_elemento": "codigosDescuento",
            "posicion": 3,
            "estado": "0",
            "content": {
                "descuentos": "Descuentos",
                "etiqueta_descuento": "Codigo de descuento",
                "textoBtn_aplicar": "Aplicar",
                "colorBtn_aplicar": "#00ff59"
            }
        };

        itemList.push(defaultCodigosDescuento);

        $('.list-group-item').each(function(index) {
            var item = {
                id_elemento: $(this).attr('id'),
                posicion: index,
                estado: $(this).data('estado') || '1',
                content: {}
            };

            $(this).find('input, select').each(function() {
                var key = this.id;
                var value = $(this).is(':checkbox') ? ($(this).is(':checked') ? 'on' : 'off') : $(this).val();
                item.content[key] = value;
            });

            $(this).find('.icon-btn.active i').each(function() {
                var iconKey = $(this).closest('.btn-group').attr('id') || $(this).closest('.form-group').attr('id');
                var iconClass = $(this).attr('class');
                item.content[iconKey] = iconClass;
            });

            $(this).find('.animation-select').each(function() {
                var animationKey = this.id;
                var animationValue = $(this).val();
                item.content[animationKey] = animationValue;
            });

            itemList.push(item);
        });

        let data = {
            items: itemList
        };

        // Enviar la información al servidor
        $.ajax({
            url: SERVERURL + 'Usuarios/actualizar_checkout',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function(response) {
                toastr.success("LOS CAMBIOS HAN SIDO GUARDADPS", "NOTIFICACIÓN", {
                    positionClass: "toast-bottom-center",
                });
            },
            error: function(xhr, status, error) {
                alert('Ha ocurrido un error al guardar los cambios.');
            }
        });
    }


    $(document).ready(function() {
        $('#saveFormState').click(saveFormState);
    });

    // Funcion para guardar sin el default
    /* function saveFormState() {
        var itemList = [];
        $('.list-group-item').each(function(index) {
            var item = {
                id_elemento: $(this).attr('id'),
                posicion: index,
                estado: $(this).data('estado') || '1', // Usar '1' como valor por defecto
                content: {}
            };

            // Capturar valores de inputs, selects, y checkboxes
            $(this).find('input, select').each(function() {
                var key = this.id;
                var value = $(this).is(':checkbox') ? ($(this).is(':checked') ? 'on' : 'off') : $(this).val();
                item.content[key] = value;
            });

            // Generalización para capturar íconos activos
            $(this).find('.icon-btn.active i').each(function() {
                var iconKey = $(this).closest('.btn-group').attr('id'); // Asume que el btn-group tiene un ID
                var iconClass = $(this).attr('class');
                item.content[iconKey] = iconClass;
            });

            itemList.push(item);
        });

        // Enviar la información al servidor
        $.ajax({
            url: '../ajax/actualizar_checkout.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(itemList),
            success: function(response) {
                alert('Los cambios han sido guardados.');
            },
            error: function(xhr, status, error) {
                alert('Ha ocurrido un error al guardar los cambios.');
            }
        });
    }

    $(document).ready(function() {
        $('#saveFormState').click(saveFormState);
    }); */

    // accion del select
    document.addEventListener('DOMContentLoaded', function() {
        // Asumiendo que el select ya existe cuando carga la página
        const alineacionTituloSelect = document.getElementById('alineacion_titulo');
        alineacionTituloSelect.addEventListener('change', function() {
            const tituloPreview = document.getElementById('tituloFormularioPreview');
            switch (this.value) {
                case '1': // Izquierda
                    tituloPreview.style.textAlign = 'left';
                    break;
                case '2': // Centro
                    tituloPreview.style.textAlign = 'center';
                    break;
                case '3': // Derecha
                    tituloPreview.style.textAlign = 'right';
                    break;
            }
        });


    });
    //boton de inconos
    $(document).ready(function() {
        /*
        // Cambiar la visibilidad del grupo de botones basado en el checkbox
        $('#mostrarIcon_nombresApellidos').change(function() {
            if ($(this).is(':checked')) {
                $('#icono_nombresApellidos').show();
            } else {
                $('#icono_nombresApellidos').hide();
            }
        });
        */

        // Evento de clic en cada botón de íconos
        setupIconButtons('icono_nombresApellidos', 'icono_nombresApellidosPreview');
        setupIconButtons('icono_telefono', 'icono_telefonoPreview');
        setupIconButtons('icono_calle_principal', 'icono_calle_principalPreview');
        setupIconButtons('icono_calle_secundaria', 'icono_calle_secundariaPreview');
        setupIconButtons('icono_barrio_referencia', 'icono_barrio_referenciaPreview');
        setupIconButtons('icono_comentario', 'icono_comentarioPreview');
        
    });
    // funcion generalizada para iconos
    function setupIconButtons(containerId, previewId) {
        // Agrega evento de clic a cada botón de ícono dentro del contenedor especificado
        $('#' + containerId + ' .icon-btn').click(function(event) {
            event.preventDefault();
            // Elimina la clase 'active' de todos los botones y la añade al botón actualmente clickeado
            $('#' + containerId + ' .icon-btn').removeClass('active');
            $(this).addClass('active');
            // Obtiene el valor del ícono seleccionado y actualiza el ícono en la vista previa
            var iconClass = $(this).find('i').attr('class');
            $('#' + previewId).html("<i class='" + iconClass + "'></i>");
        });
    }
</script>
<?php require_once './Views/templates/footer.php'; ?>