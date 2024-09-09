<style>
    body {
        height: 100vh;
        background-color: #f0f2f5;
    }

    .chat-sidebar,
    .chat-content,
    .chat-info {
        height: 100%;
        overflow-y: auto;
        background-color: #fff;
    }

    /* Estilos para el sidebar */
    .chat-sidebar {
        border-right: 1px solid #ddd;
        background-color: #f8f9fa;
    }

    .contact-item {
        padding: 10px;
        transition: background-color 0.3s ease;
    }

    .contact-item:hover {
        background-color: #e9ecef;
        cursor: pointer;
    }

    .contact-item img {
        transition: transform 0.3s ease;
    }

    .contact-item:hover img {
        transform: scale(1.1);
    }

    /* Estilos para el chat */
    .chat-content {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .chat-header {
        padding: 15px;
        background-color: #007bff;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-header img {
        border-radius: 50%;
    }

    .chat-messages {
        padding: 20px;
        flex-grow: 1;
        background-color: #f0f2f5;
        overflow-y: auto;
    }

    .message {
        max-width: 60%;
        margin-bottom: 15px;
        padding: 10px 15px;
        border-radius: 20px;
        animation: fadeIn 0.5s ease;
    }

    .message.sent {
        margin-left: auto;
        background-color: #007bff;
        color: white;
    }

    .message.received {
        background-color: #e9ecef;
    }

    /* Animación para los mensajes */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Estilos para el input del mensaje */
    .chat-input {
        padding: 15px;
        border-top: 1px solid #ddd;
        background-color: #fff;
    }

    .chat-input .form-control {
        border-radius: 30px;
        padding: 10px 20px;
    }

    .chat-input button {
        border-radius: 50%;
        background-color: #007bff;
        border: none;
        padding: 10px;
    }

    /* Estilos para la información del contacto */
    .chat-info {
        background-color: #f8f9fa;
    }

    .chat-info img {
        border-radius: 50%;
        margin-bottom: 15px;
        transition: transform 0.3s ease;
    }

    .chat-info img:hover {
        transform: scale(1.05);
    }
</style>