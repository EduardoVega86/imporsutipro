<style>
    .table {
        border-collapse: collapse;
        width: 100%;
    }

    .table th,
    .table td {
        text-align: center;
        vertical-align: middle;
        border: 1px solid #ddd;
        /* Añadir borde a celdas */
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, .05);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, .075);
    }

    .table thead th {
        background-color: <?php echo COLOR_FONDO; ?>;
        color: <?php echo COLOR_LETRAS; ?>;
    }

    .centered {
        text-align: center !important;
        vertical-align: middle !important;
    }

    .btn-excel, .btn-csv {
    background-color: #198754; /* Color verde para Excel */
    color: white;
    padding: 5px 10px; /* Ajusta el padding para reducir el tamaño */
    margin: 0; /* Elimina el margen */
    border: none; /* Elimina el borde */
  }

  .buttons-csv, .buttons-excel {
    padding: 2px 10px !important; /* Ajusta el padding para reducir el tamaño superior e inferior */
    margin: 0 !important; /* Elimina el margen */
    background-color: #198754 !important; /* Color verde para Excel */
    color: white !important;
    border-radius: 0 !important; /* Elimina los bordes redondeados si los hubiera */
  }
  .buttons-csv .fa-file-csv, .buttons-excel .fa-file-excel {
    margin-left: 5px; /* Ajusta el margen entre el icono y el texto si es necesario */
  }
</style>
<style>
    .filtros_producos {
        display: flex;
        flex-direction: row;
    }

    .primerSeccion_filtros {
        display: flex;
        flex-direction: row;
    }

    @media (max-width: 768px) {
        .filtros_producos {
            flex-direction: column;
        }

        .primerSeccion_filtros {
            flex-direction: column;
            gap: 5px;
        }
    }
</style>