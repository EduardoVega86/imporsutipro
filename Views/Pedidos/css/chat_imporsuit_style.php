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

    .notificacion_mPendientes {
        position: absolute;
        top: 10%;
        right: 5%;
        background-color: red;
        color: white;
        border-radius: 100%;
        padding: 4px 7px;
        font-size: 12px;
        font-weight: bold;
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
        resize: none;
        /* Deshabilita el cambio de tamaño manual */
        overflow-y: hidden;
        /* Oculta el scroll vertical */
        max-height: 150px;
        /* Limita la altura máxima del textarea */
        width: 100%;
        /* Asegura que ocupe el ancho completo */
        transition: height 0.2s ease;
        /* Animación suave al cambiar de tamaño */
        box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
        border: 1px solid #ccc;
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

    /* fin emoji */

    /* menu de añadir documetos */
    .floating-menu {
        padding: 10px;
        background-color: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(200, 200, 200, 0.5);
        border-radius: 12px !important;
        position: absolute;
        bottom: 60px;
        left: 50px;
        z-index: 1000;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        max-width: 180px;
        transition: opacity 0.3s ease, transform 0.2s ease;
    }

    /* Ocultar menú con una clase */
    .d-none {
        display: none;
    }

    /* Listado dentro del menú */
    .floating-menu ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    /* Elementos del menú (Documentos, Fotos y videos) */
    .floating-menu .list-group-item {
        background-color: transparent;
        border: none;
        padding: 10px;
        display: flex;
        align-items: center;
        color: rgba(0, 0, 0, 0.7);
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }

    /* Efecto al hacer hover en los elementos del menú */
    .floating-menu .list-group-item:hover {
        background-color: rgba(0, 0, 0, 0.05);
        transform: scale(1.05);
        color: rgba(0, 0, 0, 0.9);
    }

    /* Estilo del ícono dentro del menú */
    .floating-menu .list-group-item i {
        font-size: 20px;
        margin-right: 10px;
        color: rgba(0, 0, 0, 0.6);
    }

    /* Fin menu de añadir documetos */

    /* Seccion template */
    .floating-templates {
        padding: 10px;
        background-color: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(200, 200, 200, 0.5);
        border-radius: 12px !important;
        position: absolute;
        bottom: 85px;
        left: 100px;
        z-index: 1000;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        max-width: 100%;
        max-height: 300px;
        overflow-y: auto;
        transition: background-color 0.3s ease;
        /* Animación suave del fondo */
    }

    /* Elementos del menú */
    .floating-templates .template-item {
        display: block;
        padding: 12px;
        margin: 5px 0;
        background-color: rgba(240, 240, 240, 0.6);
        border-radius: 8px;
        color: rgba(0, 0, 0, 0.8);
        cursor: pointer;
        transition: background-color 0.2s ease;
        /* Transición suave al cambiar el color */
    }

    /* Efecto hover */
    .floating-templates .template-item:hover {
        background-color: rgba(200, 200, 200, 0.3);
        /* Gris claro en hover */
    }

    /* Elemento activo (navegación con teclado) */
    .floating-templates .template-item.active {
        background-color: rgba(180, 180, 180, 0.4);
        /* Fondo gris claro para el activo */
        color: rgba(0, 0, 0, 0.9);
    }

    /* Personalización del scroll */
    .floating-templates::-webkit-scrollbar {
        width: 8px;
    }

    .floating-templates::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.3);
        border-radius: 4px;
    }

    .floating-templates::-webkit-scrollbar-thumb:hover {
        background-color: rgba(0, 0, 0, 0.5);
    }

    /* fin seccion template */

    /* Input del mensaje con bordes redondeados para armonizar */
    #message-input {
        border-radius: 20px;
        padding: 10px;
        box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
        border: 1px solid #ccc;
    }

    #message-input:focus {
        outline: none;
        border-color: rgba(100, 100, 255, 0.5);
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

    /* implementacion sistema de guias */
    .absolute {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 500px;
        transition: right 0.3s ease;
    }

    .transportadoras {
        padding: .25rem;
        background-color: var(--bs-body-bg);
        border: var(--bs-border-width) solid var(--bs-border-color);
        border-radius: 0.375rem 0.375rem 0 0;
        max-width: 100%;
        height: auto;
    }

    .precio {
        background-color: rgba(0, 0, 0, 0.7);
        text-align: center;
        color: white;
        border-radius: 0 0 10px 10px;
    }

    #infoMenu {
        z-index: 1;
    }

    .transportadora {
        cursor: pointer;
    }

    .transportadora img {
        transition: filter 0.3s;
    }

    .transportadora img:hover {
        filter: grayscale(0);
    }

    #detailsMenu {
        position: relative;
        z-index: 2;
        top: 0;
        bottom: 0;
        right: -500px;
        /* Hidden by default */
        transition: right 0.3s ease;
        padding-left: 0;
        padding-right: 0;
    }

    #generateMenu {
        position: fixed;
        z-index: 2;
        top: 0;
        bottom: 0;
        right: -500px;
        /* Hidden by default */
        width: 500px;
        transition: right 0.3s ease;
    }

    .right-0 {
        right: 0;
    }

    .right-100 {
        right: -500px;
    }

    .menu_creacion_guia {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 0.25rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        padding: 1rem;
        height: 100%;
    }

    /* Contenedor transporte-item para aplicar efecto hover y seleccionado */
    .transporte-item {
        transition: transform 0.3s ease, border 0.3s ease;
        border: 2px solid transparent;
        /* Sin borde por defecto */
    }

    /* Hover: Aplica efecto a imagen y precio al mismo tiempo */
    .transporte-item:hover {
        transform: translateY(-10px);
        /* Levanta todo el contenedor */
        border: 2px solid blue;
        /* Borde azul en hover */
    }

    /* Quita el filtro grayscale en hover para la imagen */
    .transporte-item:hover .img-thumbnail,
    .transporte-item:hover .precio {
        filter: grayscale(0);
    }

    /* Al seleccionar una transportadora */
    .transporte-item.selected {
        border: 2px solid blue;
        /* Mantiene el borde azul cuando está seleccionado */
    }

    .selected {
        border: 2px solid blue;
        /* Mantiene el borde azul cuando está seleccionado */
    }

    /* También quitar el filtro grayscale cuando está seleccionado */
    .transporte-item.selected .img-thumbnail,
    .transporte-item.selected .precio {
        filter: grayscale(0);
    }

    /* Ensure the hidden class correctly hides elements */
    .hidden {
        display: none;
    }

    /* Asegura que los botones flotantes estén en la parte inferior */
    .floating-buttons {
        margin-top: auto;
    }
</style>