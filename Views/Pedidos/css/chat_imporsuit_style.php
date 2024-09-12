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

    /* emojis */
    /* Contenedor general del selector de emojis */
    .emoji-picker {
        width: 100%;
        max-width: 300px;
        background-color: #fff;
        border-radius: 10px;
        position: absolute;
        bottom: 70px;
        left: 0;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Pestañas de categorías */
    .emoji-tabs {
        display: flex;
        justify-content: space-around;
        background-color: #f0f2f5;
        padding: 10px 0;
    }

    .emoji-tab {
        background: none;
        border: none;
        color: #555;
        font-size: 18px;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .emoji-tab.active {
        color: #007bff;
    }

    .emoji-tab:hover {
        color: #007bff;
    }

    /* Contenedor de búsqueda */
    .emoji-search-container {
        padding: 10px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #ddd;
    }

    .emoji-search-container input {
        width: 100%;
        padding: 8px;
        border-radius: 20px;
        border: 1px solid #ddd;
        background-color: #fff;
        color: #333;
        outline: none;
        font-size: 14px;
    }

    /* Sección de emojis con scroll */
    .emoji-section {
        max-height: 250px;
        overflow-y: auto;
        padding: 10px;
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        grid-gap: 10px;
    }

    .emoji {
        font-size: 24px;
        cursor: pointer;
        transition: transform 0.2s ease, opacity 0.2s ease;
        text-align: center;
    }

    .emoji:hover {
        transform: scale(1.2);
        opacity: 0.8;
    }

    .d-none {
        display: none;
    }


    /* fin emojis */

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
</style>