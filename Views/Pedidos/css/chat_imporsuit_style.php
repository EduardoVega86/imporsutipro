<style>
    body {
        height: 100vh;
        background-color: #f0f2f5;
    }

    .chat-sidebar,
    .chat-info {
        overflow-y: auto;
    }

    .chat-content {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100vh;
        transition: all 0.5s ease;
        width: 100%;
        /* Ocupa todo el ancho, excepto la barra derecha */
    }

    .chat-content.expanded {
        width: calc(100% - 25%);
        /* Expande solo para cubrir el espacio del panel derecho */
    }

    .chat-sidebar {
        border-right: 1px solid #ddd;
        background-color: #f8f9fa;
        height: 100vh;
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

    .chat-info {
        background-color: #f8f9fa;
        height: 100vh;
        transition: all 0.5s ease;
        width: 25%;
        /* Visible al inicio */
        overflow: hidden;
    }

    .chat-info.hidden {
        width: 0;
        /* Oculto cuando la clase "hidden" está activa */
    }

    .close-info-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        display: none;
        /* Oculto por defecto */
    }

    .chat-info.hidden .close-info-btn {
        display: none;
        /* No mostrar botón cuando el panel está oculto */
    }

    .chat-info.visible .close-info-btn {
        display: block;
    }
</style>