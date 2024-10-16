<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/chat_imporsuit_style.php'; ?>
<?php require_once './Views/Pedidos/Modales/agregar_etiqueta.php'; ?>
<?php require_once './Views/Pedidos/Modales/asignar_etiquetas.php'; ?>

<div class="container-fluid h-100">
    <div class="row h-100">
        <!-- Sidebar izquierda: Lista de contactos -->
        <div class="col-3 p-0 chat-sidebar">
            <div class="p-3 border-bottom d-flex flex-row gap-2">
                <input type="text" class="form-control" placeholder="Buscar contacto...">
                <button type="button" data-bs-toggle="modal" data-bs-target="#agregar_etiquetaModal" class="btn btn-primary"><i class="fa-solid fa-tags"></i></button>
            </div>
            <ul class="list-group list-group-flush" id="contact-list">
                <!-- Los contactos se llenarán aquí dinámicamente -->
            </ul>
        </div>

        <!-- Centro: Conversación del chat -->
        <div class="col-6 p-0 chat-content full-width">
            <div class="chat-header">
                <div class="d-flex align-items-center">
                    <img src="https://new.imporsuitpro.com/public/img/avatar_usuaro_chat_center.png" class="rounded-circle me-3" alt="Foto de perfil" style="width: 10% !important;">
                    <div class="d-flex flex-column">
                        <h5 class="mb-0"><span id="nombre_chat"></span></h5>
                        <h6 class="mb-0"><span id="telefono_chat"></span></h6>
                    </div>
                    <input type="hidden" id="id_cliente_chat" name="id_cliente_chat">
                    <input type="hidden" id="id_etiqueta_select" name="id_etiqueta_select">
                    <input type="hidden" id="celular_chat" name="celular_chat">
                    <input type="hidden" id="uid_cliente" name="uid_cliente">

                    <input type="hidden" id="id_whatsapp" name="id_whatsapp">
                    <input type="hidden" id="token_configruacion" name="token_configruacion">

                </div>
                <i class="fas fa-ellipsis-v toggle-info" id="btn-three-dots"></i> <!-- Botón de tres puntos -->
            </div>

            <!-- chat -->
            <div class="chat-messages">

            </div>
            <!-- fin chat -->

            <div class="chat-input border-top position-relative">
                <div class="input-group">
                    <!-- Sección de emojis que se despliega al hacer clic en la carita sonriente -->
                    <div id="emoji-section" class="emoji-section d-none">
                        <!-- Input para buscar emojis -->
                        <input id="emoji-search" type="text" class="form-control" placeholder="Buscar emojis..." style="margin-bottom: 10px; border-radius: 12px; padding: 8px;">
                        <!-- Contenedor para los emojis -->
                        <div id="emoji-list"></div> <!-- Aquí se cargarán los emojis -->
                    </div>

                    <!-- Botón de carita sonriente -->
                    <button id="emoji-button" class="btn btn-emoji">
                        <i class="fas fa-smile"></i>
                    </button>

                    <!-- boton para subir imagenes archivos-->
                    <!-- Botón de "+" -->
                    <button id="document-button" class="btn btn-emoji">
                        <i class="bx bx-plus"></i>
                    </button>

                    <!-- Menú flotante para documentos, fotos y videos -->
                    <div id="floating-menu" class="floating-menu d-none">
                        <ul class="list-group">
                            <li class="list-group-item d-flex align-items-center" id="agregar_documento">
                                <i class="fas fa-file-alt me-2"></i> Documento
                            </li>
                            <li class="list-group-item d-flex align-items-center" id="agregar_foto">
                                <i class="fa-solid fa-image me-2"></i> Fotos
                            </li>
                            <li class="list-group-item d-flex align-items-center" id="agregar_video">
                                <i class="fa-solid fa-film me-2"></i> Videos
                            </li>
                        </ul>
                    </div>

                    <!-- Input oculto para seleccionar una imagen -->
                    <input type="file" id="foto-input" accept="image/*" style="display: none;">

                    <!-- Campo de texto del mensaje -->
                    <div class="flex-grow-1 chat-input">
                        <textarea id="message-input" class="form-control"
                            placeholder="Escribe un mensaje..."></textarea>
                    </div>

                    <!-- Botón de enviar -->
                    <button id="send-button" class="btn btn-primary ms-2" style="display: none; border-radius: 0.7rem;">
                        <i class="fas fa-paper-plane"></i>
                    </button>

                    <!-- Botón de grabar -->
                    <button id="record-button" class="btn btn-primary ms-2" style="border-radius: 0.7rem;">
                        <i id="icon-record" class="fa-solid fa-microphone"></i>
                    </button>

                    <!-- Contenedor de la grabación de audio -->
                    <div id="audio-recording-controls" class="d-none">
                        <span id="audio-timer">0:00</span>
                        <button id="pause-recording" class="btn">
                            <i class="fa fa-pause"></i>
                        </button>
                        <button id="stop-recording" class="btn">
                            <i class="fa fa-stop"></i>
                        </button>
                        <button id="send-audio" class="btn">
                            <i class="fa fa-paper-plane"></i>
                        </button>
                    </div>

                </div>
            </div>

        </div>

        <!-- Derecha: Información del contacto -->
        <div class="col-3 p-0 chat-info d-flex flex-column align-items-center position-relative hidden" id="infoMenu">
            <div class="p-3 text-center">
                <div class="dropdown" style="position: absolute; left: 10px; top: 10px;">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="abrir_modal_etiquetas()">Agregar etiqueta</span></li>
                    </ul>
                </div>
                <button class="close-info" id="btn-close-info">&times;</button> <!-- Botón de cierre (X) -->
                <img src="https://new.imporsuitpro.com/public/img/avatar_usuaro_chat_center.png" class="rounded-circle" alt="Foto de perfil" style="width: 40% !important;">
                <h5><span id="telefono_info"></span></h5>
                <!-- <p class="text-muted">Última vez en línea: hace 5 minutos</p> -->
            </div>
            <div class="p-3">
                <h4>Detalles</h4>
                <p><span id="nombre_info"></span></p>
                <p><span id="correo_info"></span></p>
            </div>

            <!-- Sección adicional que aparece al hacer clic en los botones flotantes -->
            <div class="info-section">
                <h6>Historial Pedidos</h6>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Número Factura</th>
                            <th>Nombre Cliente</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="historialPedidosBody">
                        <!-- Aquí se inyectarán las filas de los pedidos -->
                    </tbody>
                </table>
            </div>


            <div class="tools-section">
                <h6>Herramientas</h6>
                <p>Funciones adicionales como editar, eliminar contacto, etc.</p>
            </div>

            <!-- Botones flotantes -->
            <div class="floating-buttons">
                <button id="btn-info"><i class="fas fa-info-circle"></i></button>
                <button id="btn-tools"><i class="fas fa-wrench"></i></button>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="productosModal" tabindex="-1" aria-labelledby="productosModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="productosModalLabel">AGREGAR PRODUCTOS A PEDIDO</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="detailsMenu" style="display: none;" class="col-3">
            <div class="menu_creacion_guia">
                <div class="accordion mt-3" id="accordionDetailsMenu">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingPedido">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapsePedido" aria-expanded="true" aria-controls="collapsePedido">
                                Datos del pedido
                            </button>
                        </h2>
                        <div id="collapsePedido" class="accordion-collapse collapse show" aria-labelledby="headingPedido"
                            data-bs-parent="#accordionDetailsMenu">
                            <div class="accordion-body">
                                <!-- Loader -->
                                <div id="loadingIndicator" class=" justify-content-center align-items-center"
                                    style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 10;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                </div>

                                <!-- hiddens -->
                                <input type="hidden" id="nombreO">
                                <input type="hidden" id="ciudadO">
                                <input type="hidden" id="provinciaO">
                                <input type="hidden" id="direccionO">
                                <input type="hidden" id="celularO">
                                <input type="hidden" id="referenciaO">
                                <input type="hidden" id="numero_factura">
                                <input type="hidden" id="precio_envio">
                                <input type="hidden" id="monto_factura">
                                <input type="hidden" id="flete">
                                <input type="hidden" id="seguro">
                                <input type="hidden" id="comision">
                                <input type="hidden" id="otros">
                                <input type="hidden" id="impuestos">


                                <div class="row g-3">
                                    <!-- Nombre del cliente -->
                                    <div class="col-md-6">
                                        <label for="nombre_cliente" class="form-label">Nombre del
                                            cliente:</label>
                                        <input type="text" class="form-control" id="frm_nombre_cliente"
                                            placeholder="Ingresa el nombre">
                                    </div>
                                    <!-- Teléfono -->
                                    <div class="col-md-6">
                                        <label for="telefono" class="form-label">Teléfono:</label>
                                        <input type="text" class="form-control" id="frm_telefono"
                                            placeholder="Ingresa el teléfono">
                                    </div>
                                    <!-- provincia -->
                                    <div class="col-md-6">
                                        <label for="provincia" class="form-label">Provincia:</label>
                                        <select class="form-select" id="frm_provincia"
                                            onchange="llenarCiudades(this.value)">
                                            <option value="" selected>Selecciona una provincia</option>
                                        </select>
                                    </div>

                                    <!-- ciudad -->
                                    <div class="col-md-6">
                                        <label for="ciudad" class="form-label">Ciudad:</label>
                                        <select class="form-select" id="frm_ciudad">
                                            <option value="" selected>Selecciona una ciudad</option>
                                        </select>
                                    </div>

                                    <!-- calle principal -->
                                    <div class="col-md-6">
                                        <label for="calle_principal" class="form-label">Calle principal:</label>
                                        <input type="text" class="form-control" id="frm_calle_principal"
                                            placeholder="Ingresa la calle principal">
                                    </div>

                                    <!-- calle secundaria -->
                                    <div class="col-md-6">
                                        <label for="calle_secundaria" class="form-label">Calle
                                            secundaria:</label>
                                        <input type="text" class="form-control" id="frm_calle_secundaria"
                                            placeholder="Ingresa la calle secundaria">
                                    </div>

                                    <!-- referencia -->
                                    <div class="col-md-6">
                                        <label for="referencia" class="form-label">Referencia:</label>
                                        <input type="text" class="form-control" id="frm_referencia"
                                            placeholder="Ingresa la referencia">
                                    </div>

                                    <!-- observacion-->
                                    <div class="col-md-6">
                                        <label for="observacion" class="form-label">Observación:</label>
                                        <input type="text" class="form-control" id="frm_observacion"
                                            placeholder="Ingresa la observación">
                                    </div>

                                    <!-- recaudacion-->
                                    <div class="col-md">
                                        <label for="recaudacion" class="form-label">Recaudación:</label>
                                        <select class="form-select" id="frm_recaudacion">
                                            <option value="">Selecciona una recaudación</option>
                                            <option value="1">Con recaudo</option>
                                            <option value="0">Sin recaudo</option>
                                        </select>
                                    </div>

                                    <h3>Selecciona una transportadora:</h3>
                                    <div class="row">
                                        <div class="col-6 col-md-3 transporte-item">
                                            <img style="filter: grayscale(100);"
                                                src="https://new.imporsuitpro.com/public/img/SERVIENTREGA.jpg"
                                                alt="Transportadora 1" class="transportadoras transportadora"
                                                data-value="transportadora1">
                                            <p class="precio transportadora1" id="precio_transporte_servi">$0</p>
                                        </div>
                                        <div class="col-6 col-md-3 transporte-item">
                                            <img style="filter: grayscale(100);"
                                                src="https://new.imporsuitpro.com/public/img/LAAR.jpg"
                                                alt="Transportadora 2" class="transportadoras transportadora"
                                                data-value="transportadora2">
                                            <p class="precio transportadora2" id="precio_transporte_laar">$0</p>
                                        </div>
                                        <div class="col-6 col-md-3 transporte-item">
                                            <img style="filter: grayscale(100);"
                                                src="https://new.imporsuitpro.com/public/img/SPEED.jpg"
                                                alt="Transportadora 3" class="transportadoras transportadora"
                                                data-value="transportadora3">
                                            <p class="precio transportadora3" id="precio_transporte_speed">$0</p>
                                        </div>
                                        <div class="col-6 col-md-3 transporte-item">
                                            <img style="filter: grayscale(100);"
                                                src="https://new.imporsuitpro.com/public/img/GINTRACOM.jpg"
                                                alt="Transportadora 4" class="transportadoras transportadora"
                                                data-value="transportadora4">
                                            <p class="precio transportadora4" id="precio_transporte_gintracom">$0</p>
                                        </div>
                                    </div>


                                    <form id="formTransportadora" class="mt-3">
                                        <input type="hidden" id="selectedTransportadora" name="selectedTransportadora">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingProductos">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseProductos" aria-expanded="false" aria-controls="collapseProductos">
                                Productos
                            </button>
                        </h2>
                        <div id="collapseProductos" class="accordion-collapse collapse" aria-labelledby="headingProductos"
                            data-bs-parent="#accordionDetailsMenu">
                            <div class="accordion-body">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Total</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productosBody">
                                        <!-- Aquí se añadirán los productos dinámicamente -->
                                    </tbody>
                                </table>
                                <button class="btn btn-success justify-content-end" data-bs-toggle="modal"
                                    data-bs-target="#productosModal">Agregar
                                    producto</button>
                            </div>
                            <span class="text-end">
                                <span class="text-end">
                                    Total a pagar: <span id="totalPagar"></span>
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="position-absolute w-100 bottom-0 start-0 p-3 bg-white border-top">
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-primary w-100" type="button" onclick="generarGuia()">Generar
                                    guía</button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-danger w-100" type="button" onclick="cancelarPedido()">Cancelar
                                    pedido</button>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button class="btn btn-outline-danger w-100" id="closeMenu">Retroceder</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/chat_imporsuit.js"></script>
<?php require_once './Views/templates/footer.php'; ?>