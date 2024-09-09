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
                <li class="list-group-item contact-item">
                    <div class="d-flex align-items-center">
                        <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="Foto de perfil">
                        <div>
                            <h6 class="mb-0">+123 456 7890</h6>
                            <small class="text-muted">Último mensaje...</small>
                        </div>
                    </div>
                </li>
                <li class="list-group-item contact-item">
                    <div class="d-flex align-items-center">
                        <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="Foto de perfil">
                        <div>
                            <h6 class="mb-0">+987 654 3210</h6>
                            <small class="text-muted">Último mensaje...</small>
                        </div>
                    </div>
                </li>
                <!-- Más contactos -->
            </ul>
        </div>

        <!-- Centro: Conversación del chat -->
        <div class="col-6 p-0 chat-content">
            <div class="p-3 bg-light border-bottom">
                <h5 class="mb-0">Selecciona un contacto para chatear</h5>
            </div>
            <div class="p-3">
                <!-- Aquí aparecerán los mensajes del chat -->
                <div class="d-flex justify-content-start mb-3">
                    <div class="p-3 bg-light rounded">
                        Hola, ¿cómo estás?
                    </div>
                </div>
                <div class="d-flex justify-content-end mb-3">
                    <div class="p-3 bg-primary text-white rounded">
                        Bien, ¿y tú?
                    </div>
                </div>
            </div>
            <div class="p-3 border-top">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Escribe un mensaje...">
                    <button class="btn btn-primary">Enviar</button>
                </div>
            </div>
        </div>

        <!-- Derecha: Información del contacto -->
        <div class="col-3 p-0 chat-info">
            <div class="p-3">
                <img src="https://via.placeholder.com/150" class="rounded-circle mb-3" alt="Foto de perfil">
                <h5>+123 456 7890</h5>
                <p class="text-muted">Última vez en línea: hace 5 minutos</p>
                <hr>
                <h6>Detalles</h6>
                <p>Nombre: John Doe</p>
                <p>Ubicación: Ciudad, País</p>
                <p>Correo: johndoe@email.com</p>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo SERVERURL ?>/Views/Pedidos/js/chat_imporsuit.js"></script>
<?php require_once './Views/templates/footer.php'; ?>