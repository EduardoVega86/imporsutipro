<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/bodegas_style.php';
session_start(); ?>

<script>
    function redirect(direccion) {
        let token = "<?= $_SESSION['token'] ?>";
        let ruta = "";
        if (direccion === 'herramientas') {
            ruta = "https://herramientas.imporfactory.app/newlogin?token=" + token;
        } else if (direccion === 'plataformas') {
            ruta = "https://cursos.imporfactory.app/newlogin?token=" + token;
        } else if (direccion === 'cotizador') {
            ruta = "https://cotizador.imporfactory.app/newlogin?token=" + token;
        } else if (direccion === 'infoaduana') {
            ruta = "https://infoaduana.imporfactory.app/newlogin?token=" + token;
        }

        // redirecciona a la ruta
        window.location.href = ruta;
    }
</script>

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