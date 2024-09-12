<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Pedidos/css/chat_imporsuit_style.php'; ?>

<div class="container-fluid h-100">
    <div class="row h-100">
        <!-- Sidebar izquierda: Lista de contactos -->
        <div class="col-3 p-0 chat-sidebar">
            <div class="p-3 border-bottom">
                <input type="text" class="form-control" placeholder="Buscar contacto...">
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item contact-item d-flex align-items-center">
                    <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="Foto de perfil">
                    <div>
                        <h6 class="mb-0">+123 456 7890</h6>
                        <small class="text-muted">Último mensaje...</small>
                    </div>
                </li>
                <!-- Más contactos -->
            </ul>
        </div>

        <!-- Centro: Conversación del chat -->
        <div class="col-6 p-0 chat-content full-width">
            <div class="chat-header">
                <div class="d-flex align-items-center">
                    <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="Foto de perfil">
                    <h5 class="mb-0">Tony Plaza</h5>
                </div>
                <i class="fas fa-ellipsis-v toggle-info" id="btn-three-dots"></i> <!-- Botón de tres puntos -->
            </div>

            <div class="chat-messages">
                <div class="message received">
                    Hola, ¿cómo estás?
                </div>
                <div class="message sent">
                    Bien, ¿y tú?
                </div>
                <div class="message sent">
                    contestame :c
                </div>
            </div>

            <div class="chat-input border-top">
                <div class="input-group">
                    <!-- Sección de emojis que se despliega al hacer clic en la carita sonriente -->
                    <div id="emoji-section" class="emoji-section d-none">
                        <span class="emoji">😀</span>
                        <span class="emoji">😁</span>
                        <span class="emoji">😂</span>
                        <span class="emoji">😍</span>
                        <span class="emoji">😎</span>
                        <span class="emoji">😭</span>
                        <span class="emoji">😡</span>
                        <!-- Agrega más emojis según sea necesario -->
                    </div>

                    <!-- Ícono de carita sonriente -->
                    <button id="emoji-button" class="btn btn-light">
                        <i class="fas fa-smile"></i>
                    </button>

                    <!-- Campo de texto del mensaje -->
                    <input id="message-input" type="text" class="form-control" placeholder="Escribe un mensaje...">

                    <!-- Botón de enviar -->
                    <button id="send-button" class="btn btn-primary ms-2">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>

        </div>

        <!-- Derecha: Información del contacto -->
        <div class="col-3 p-0 chat-info d-flex flex-column align-items-center position-relative hidden">
            <div class="p-3 text-center">
                <button class="close-info" id="btn-close-info">&times;</button> <!-- Botón de cierre (X) -->
                <img src="https://via.placeholder.com/150" class="rounded-circle" alt="Foto de perfil">
                <h5>+123 456 7890</h5>
                <p class="text-muted">Última vez en línea: hace 5 minutos</p>
            </div>
            <div class="p-3">
                <h6>Detalles</h6>
                <p>Nombre: Tony Plaza</p>
                <p>Ubicación: Ciudad, País</p>
                <p>Correo: johndoe@email.com</p>
            </div>

            <!-- Sección adicional que aparece al hacer clic en los botones flotantes -->
            <div class="info-section">
                <h6>Información adicional</h6>
                <p>Aquí puedes mostrar información relevante sobre el contacto.</p>
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