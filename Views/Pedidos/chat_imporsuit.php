<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/chat_imporsuit_style.php'; ?>

<div class="container-fluid h-100">
    <div class="row h-100">
        <!-- Sidebar izquierda: Lista de contactos -->
        <div class="col-3 p-0 chat-sidebar">
            <div class="p-3 border-bottom">
                <input type="text" class="form-control" placeholder="Buscar contacto...">
            </div>
            <ul class="list-group list-group-flush" id="contact-list">
                <!-- Los contactos se llenarán aquí dinámicamente -->
            </ul>
        </div>

        <!-- Centro: Conversación del chat -->
        <div class="col-6 p-0 chat-content full-width">
            <div class="chat-header">
                <div class="d-flex align-items-center">
                    <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="Foto de perfil">
                    <h5 class="mb-0"><span id="nombre_chat"></span></h5>
                    <input type="hidden" id="id_cliente_chat" name="id_cliente_chat">
                    <input type="hidden" id="celular_chat" name="celular_chat">
                    <input type="hidden" id="uid_cliente" name="uid_cliente">

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

                    <!-- Campo de texto del mensaje -->
                    <input id="message-input" type="text" class="form-control" placeholder="Escribe un mensaje...">

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
        <div class="col-3 p-0 chat-info d-flex flex-column align-items-center position-relative hidden">
            <div class="p-3 text-center">
                <button class="close-info" id="btn-close-info">&times;</button> <!-- Botón de cierre (X) -->
                <img src="https://via.placeholder.com/150" class="rounded-circle" alt="Foto de perfil">
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
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/chat_imporsuit.js"></script>
<?php require_once './Views/templates/footer.php'; ?>