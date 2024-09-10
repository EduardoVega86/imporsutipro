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
            </ul>
        </div>

        <!-- Centro: Conversación del chat -->
        <div class="col-6 p-0 chat-content" id="chat-content">
            <div class="chat-header">
                <div class="d-flex align-items-center">
                    <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="Foto de perfil">
                    <h5 class="mb-0">Tony Plaza</h5>
                </div>
                <i class="fas fa-ellipsis-v"></i>
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
                    <input type="text" class="form-control" placeholder="Escribe un mensaje...">
                    <button class="btn btn-primary ms-2">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Derecha: Información del contacto -->
        <div class="col-3 p-0 chat-info d-flex flex-column align-items-center position-relative" id="info-panel">
            <div class="p-3 text-center">
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
            <button id="close-info" class="close-info-btn">X</button> <!-- Botón X para cerrar -->
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/chat_imporsuit.js"></script>
<?php require_once './Views/templates/footer.php'; ?>