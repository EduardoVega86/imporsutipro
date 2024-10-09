<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/bodegas_style.php';
session_start(); ?>



<!-- genera 4 botones con funciones -->
<div class="custom-container-fluid">
    <div class="container mt-5" style="max-width: 1600px;">
        <h2 class="text-center mb-4">Bodegas</h2>
        <button onclick="redirect('herramientas')" class="btn btn-success">
            <i class="fas fa-plus"></i> Agregar
        </button>
        <button onclick="redirect('plataformas')" class="btn btn-success">
            <i class="fas fa-plus"></i> Agregar
        </button>
        <button onclick="redirect('cotizador')" class="btn btn-success">
            <i class="fas fa-plus"></i> Agregar
        </button>
        <button onclick="redirect('infoaduana')" class="btn btn-success">
            <i class="fas fa-plus"></i> Agregar
        </button>
    </div>
</div>

<?php require_once './Views/templates/footer.php'; ?>