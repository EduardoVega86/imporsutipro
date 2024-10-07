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
        /* Transición suave */
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

    .image-mensaje {
        max-width: 330px;
        max-height: 426px;
    }

    .video_style_mensaje {
        max-width: 330px;
        max-height: 426px;
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
        /* Transición suave */
    }

    .chat-info.hidden {
        width: 0;
        padding: 0;
        overflow: hidden;
    }

    .chat-content.full-width {
        width: 75%;
    }

    .chat-info img {
        border-radius: 50%;
        margin-bottom: 15px;
        transition: transform 0.3s ease;
    }

    .chat-info img:hover {
        transform: scale(1.05);
    }

    /* reproductor de audios mensajes recibidos */
    .audio-player {
        width: 100%;
        max-width: 250px;
        margin-top: 10px;
    }

    .audio-time {
        font-size: 12px;
        color: #999;
        margin-top: 5px;
    }

    /* fin reproductos de audios mensajes recibidos */

    /* Seccion de descargar documentos mensajes */
    .document-container {
        display: flex;
        align-items: center;
        padding: 10px;
        background-color: #f0f2f5;
        border-radius: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
    }

    .document-icon {
        font-size: 30px;
        color: #ff5722;
        margin-right: 15px;
    }

    .document-info {
        flex-grow: 1;
    }

    .document-name {
        font-weight: bold;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .document-details {
        font-size: 12px;
        color: #777;
    }

    .document-download {
        font-size: 20px;
        color: #007bff;
        text-decoration: none;
        margin-left: 15px;
    }

    .document-text {
        font-size: 13px;
        color: #333;
        margin-top: 5px;
    }

    /* Fin Seccion de descargar documentos mensajes */

    /* emojis */
    /* Diseño delicado y elegante para la sección de emojis */
    .emoji-section {
        padding: 10px;
        background-color: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(200, 200, 200, 0.5);
        border-radius: 12px;
        position: absolute;
        bottom: 60px;
        left: 10px;
        z-index: 1000;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        max-height: 200px;
        overflow-y: auto;
        transition: opacity 0.3s ease;
    }

    .emoji {
        cursor: pointer;
        font-size: 22px;
        /* Un poco más pequeño */
        transition: transform 0.2s ease, opacity 0.2s ease;
        text-align: center;
        /* Centra el emoji en su celda */
    }

    .emoji:hover {
        transform: scale(1.2);
        opacity: 0.8;
    }

    /* Diseño para el botón de la carita sonriente */
    .btn-emoji {
        background-color: transparent !important;
        border: none;
        padding: 5px;
        font-size: 24px;
        color: rgba(0, 0, 0, 0.5);
        /* Color inicial semitransparente */
        transition: color 0.3s ease, transform 0.3s ease;
        position: relative;
    }

    .btn-emoji:hover {
        color: rgba(0, 0, 0, 0.8);
        /* Color más oscuro al hacer hover */
        transform: scale(1.1);
        /* Efecto de agrandar suavemente */
    }

    .btn-emoji:focus {
        outline: none;
        /* Quitar el contorno feo por defecto */
    }

    /* Input del mensaje con bordes redondeados para armonizar */
    #message-input {
        border-radius: 20px;
        padding: 10px;
        box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    #message-input:focus {
        outline: none;
        border-color: rgba(100, 100, 255, 0.5);
        /* Color sutil cuando está enfocado */
    }

    /* Sombra para el botón de enviar */
    .btn-primary {
        box-shadow: 0 4px 12px rgba(0, 0, 255, 0.3);
    }

    .btn-primary:hover {
        box-shadow: 0 6px 18px rgba(0, 0, 255, 0.4);
    }

    /* Transiciones suaves */
    .d-none {
        opacity: 0;
        pointer-events: none;
    }

    .emoji-section:not(.d-none) {
        opacity: 1;
        pointer-events: auto;
    }

    /* Estilo del buscador dentro de la sección de emojis */
    #emoji-search {
        width: 100%;
        margin-bottom: 10px;
        padding: 8px;
        font-size: 16px;
        border-radius: 12px;
        border: 1px solid rgba(200, 200, 200, 0.8);
        box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* fin emojis */

    /* audio */
    #audio-recording-controls {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #f1f1f1;
        padding: 10px;
        border-radius: 10px;
    }

    #audio-timer {
        font-size: 16px;
        font-weight: bold;
    }

    .d-none {
        display: none;
    }

    /* Fin audio */

    /* Botones flotantes */
    .floating-buttons {
        position: absolute;
        bottom: 20px;
        right: 20px;
        display: flex;
        gap: 15px;
    }

    .floating-buttons button {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: none;
        background-color: #007bff;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .floating-buttons button:hover {
        background-color: #0056b3;
        transform: scale(1.1);
    }

    /* Sección oculta que se muestra al hacer clic */
    .info-section,
    .tools-section {
        display: none;
        padding: 20px;
        background-color: #fff;
        border-top: 1px solid #ddd;
        animation: slideDown 0.5s ease forwards;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Botón de cerrar (X) */
    .close-info {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        font-size: 24px;
        font-weight: bold;
        color: #333;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close-info:hover {
        color: #007bff;
    }

    /* Aumentar tamaño del botón de los tres puntos */
    #btn-three-dots {
        font-size: 24px;
        /* Aumenta el tamaño del ícono */
        cursor: pointer;
    }

    #btn-three-dots:hover {
        color: #007bff;
        /* Añade un efecto de hover */
    }

    /* Esconder el radio button de las etiquetas (solo para este modal) */
    .asignar-etiqueta-form-check-input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    /* Estilo de las tarjetas de etiquetas (solo para este modal) */
    .asignar-etiqueta-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        margin-bottom: 15px;
        background-color: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .asignar-etiqueta-item:hover {
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        background-color: #f8f9fa;
    }

    /* Estilo cuando el radio está seleccionado */
    .asignar-etiqueta-form-check-input:checked+.asignar-etiqueta-item {
        border-color: #ffc107;
        background-color: #fff7e6;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .asignar-etiqueta-color {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 15px;
        flex-shrink: 0;
        border: 2px solid #e0e0e0;
    }

    .asignar-etiqueta-nombre {
        font-size: 16px;
        font-weight: 500;
        color: #333;
    }

    /* Icono de selección (solo se muestra cuando se selecciona una etiqueta) */
    .asignar-check-icon {
        display: none;
        font-size: 20px;
        color: #ffc107;
    }

    .asignar-etiqueta-form-check-input:checked+.asignar-etiqueta-item .asignar-check-icon {
        display: block;
    }
</style>