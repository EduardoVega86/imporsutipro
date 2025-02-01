<style>
   
    /* cards */
    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(275px, 1fr));
        gap: 10px;
    }

    .card-custom {
        position: relative;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        height: 520px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        width: 100%;
    }

    .card-custom:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
    }

    .card-custom img {
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        height: 200px;
        width: 100%;
        object-fit: cover;
        flex-shrink: 0;
    }

    .card-custom .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
    }

    .card-custom .btn-description,
    .card-custom .btn-import {
        border-radius: 50px;
        padding: 10px 20px;
        margin: 5px auto;
        width: 80%;
    }

    .card-id-container {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 5px;
        padding: 5px 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
        cursor: pointer;
        z-index: 10;
    }

    .card-id {
        font-size: 14px;
        font-weight: bold;
        color: #333;
    }

    .btn-description {
        background-color: #00aaff;
        color: white;
    }

    .btn-import {
        background-color: #ffc107;
        color: white;
        margin-bottom: 10px;
    }

    .card-text {
        margin-bottom: 1px;
    }

    .modal-content {
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: #f8f9fa;
        color: #343a40;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .modal-header .btn-close {
        color: white;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        border-top: none;
        padding: 10px 20px;
    }

    .modal-footer .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .modal-footer .btn-primary {
        background-color: #ffc107;
        border-color: #ffc107;
        color: white;
    }

    .texto_modal {
        font-size: 20px;
        margin-bottom: 5px;
    }

    /* carrusel responsive */
   

   

    .slider-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #e0e0e0;
        color: #333;
        min-width: 283px;
        height: 105px;
        border: 1px solid #ccc;
        border-radius: 20px;
        cursor: pointer;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        text-align: center;
        padding: 10px;
        box-sizing: border-box;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .slider-chip img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        margin-right: 10px;
        border-radius: 50%;
    }

    .slider-chip:hover {
        background-color: #ccc;
        transform: scale(1.05);
    }

    .slider-chip.selected {
        background-color: rgb(91, 158, 230);
        color: white;
        transform: scale(1.05);
    }

    .slider-proveedores::-webkit-scrollbar {
        height: 6px;
    }

    .slider-proveedores::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 3px;
    }

    /* Responsive design */
    @media (max-width: 1024px) {
        .slider-proveedores {
            max-width: 100%;
            padding: 10px;
        }
        .slider-chip {
            min-width: 200px;
        }
        .card-container {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }
        .card-custom {
            height: auto;
        }
    }

    @media (max-width: 768px) {
        .slider-proveedores {
            overflow-x: scroll;
        }
        .slider-chip {
            min-width: 150px;
            height: auto;
            padding: 8px;
        }
        .slider-chip img {
            width: 30px;
            height: 30px;
        }
        .card-custom img {
            height: 150px;
        }
        .card-custom {
            padding: 10px;
        }
    }

    @media (max-width: 480px) {
        .slider-chip {
            min-width: 120px;
            padding: 5px;
        }
        .slider-chip img {
            width: 20px;
            height: 20px;
        }
        .card-container {
            grid-template-columns: 1fr;
        }
        .card-custom {
            height: auto;
        }
    }
</style>
