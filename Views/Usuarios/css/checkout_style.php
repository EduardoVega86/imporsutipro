<style>
    .seccion_principal {
        display: flex;
        flex-direction: row;
    }

    .left-column {
        width: 50%;
        padding: 20px;
        padding-top: 60px;
        position: -webkit-sticky;
        /* Para compatibilidad con Safari */
        position: sticky;
        top: 0;
        /* Ajusta esto a la altura de cualquier cabecera o menú que tengas */
        height: 100%;
        /* O la altura que quieras que tenga */
    }

    .right-column {
        width: 50%;
        padding: 20px;
        padding-top: 60px;
    }

    /* Seccion Hidden */
    .list-group-item {
        display: flex;
        flex-direction: column;
        /* Asegura que el contenido fluya de arriba hacia abajo */
    }

    .edit-section {
        width: 100%;
        /* Ocupa todo el ancho disponible */
        /* Otros estilos que desees aplicar */
    }

    .hidden {
        display: none;
        /* Oculta la sección */
    }

    /* Este estilo se aplica cuando se muestra la sección */
    .edit-section:not(.hidden) {
        display: block;
        /* O 'flex' si necesitas más control sobre el contenido interior */
    }

    .caja_transparente {
        border-radius: 0.5rem;
        border: 1px solid #ccc;
        padding: 10px;
    }

    .caja_variable {
        padding-top: 10px;
        padding-right: 10px !important;
        padding-left: 10px !important;
        border-radius: 0.5rem;
        background-color: #dedbdb;
    }

    .caja_oferta {
        padding: 10px;
        border-radius: 0.5rem;
        background-color: rgba(0, 164, 251, 0.5);
        /* 50% de opacidad */
    }

    .discount-code-container {
        max-width: 300px;
        /* O el ancho que prefieras */
        padding-top: 10px;
    }

    .applied-discount {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        padding: 5px 10px;
        background: #f2f2f2;
        /* Fondo gris claro para destacar */
        border-radius: 5px;
    }

    .discount-tag {
        font-weight: bold;
    }

    .close {
        font-size: 20px;
        color: #000;
        opacity: 0.6;
    }

    .close:hover {
        opacity: 1;
    }

    .sub_titulos {
        font-size: 17px;
        font-weight: 700;
    }

    hr {
        border: none;
        /* Quita el borde predeterminado */
        height: 2px;
        /* Ajusta el grosor de la línea */
        background-color: #000;
        /* Ajusta el color de la línea */
        margin: 15px 0;
        /* Ajusta el espaciado vertical de la línea */
    }

    .input-group-text {
        background: transparent;
        padding-right: 10px;
        /* Ajusta el espacio a la derecha del ícono si es necesario */
        border: 1px solid #ced4da;
        /* Ajusta al color de borde deseado */
        border-right: none;
        /* Remueve el borde derecho del span */
        border-radius: 0.25rem 0 0 0.25rem;
        /* Ajusta el radio del borde */
        height: 100%;
    }

    .form-group .input-group .form-control {
        border: 1px solid #ced4da;
        /* Ajusta al color de borde deseado */
        border-left: none;
        /* Remueve el borde izquierdo donde se unen el ícono y el input */
        border-radius: 0 0.25rem 0.25rem 0;
        /* Ajusta el radio del borde */
        padding-left: 10px;
        /* Ajusta el espacio a la izquierda del texto */
    }

    .icon-btn.active i {
        color: white;
        /* O puedes usar #FFFFFF */
    }

    .form-group {
        margin: 0 !important;
    }

    .btn_comprar {
        border-radius: 0.5rem;
        padding: 10px;
    }

    /* CSS para tachar codigo de descuentro*/
    #codigosDescuento_temporal .d-flex {
        text-decoration: line-through;
        color: red;
        /* Cambia el color del texto a rojo */
    }

    #codigosDescuento_temporal button {
        pointer-events: none;
        /* Desactiva los eventos del ratón, haciendo los botones no clickeables */
        opacity: 0.5;
        /* Cambia la opacidad para mostrar que están desactivados */
    }

    /* animaciones del boton comprar */
    /* Animación Bounce */
    .bounce {
        animation: bounce 1.2s ease-in-out infinite;
    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    /* Animación Shake */
    .shake {
        animation: shake 6s linear infinite;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        10%,
        20%,
        30%,
        40%,
        50%,
        60%,
        70%,
        80%,
        90% {
            transform: translateX(5px);
        }

        15%,
        25%,
        35%,
        45%,
        55%,
        65%,
        75%,
        85%,
        95% {
            transform: translateX(-5px);
        }
    }

    /* Animación Pulse */
    .pulse {
        animation: pulse 1s ease-in-out infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    @media (max-width: 768px) {
        .seccion_principal {
            flex-direction: column;
        }

        .left-column {
            position: relative;
            width: 100%;
        }

        .right-column {
            width: 100%;
        }
    }
</style>