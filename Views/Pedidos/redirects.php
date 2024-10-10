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

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
        }
        // redirecciona a la ruta
        window.location.href = ruta;

    });

    function redirect(direccion) {
        let token = "<?= $_SESSION['token'] ?>";
        let ruta = "";

        if (direccion === 'herramientas') {
            ruta = "https://herramientas.imporfactory.app/newlogin";
        } else if (direccion === 'plataformas') {
            ruta = "https://cursos.imporfactory.app/newlogin";
        } else if (direccion === 'cotizador') {
            ruta = "https://cotizador.imporfactory.app/newlogin";
        } else if (direccion === 'infoaduana') {
            ruta = "https://infoaduana.imporfactory.app/newlogin";
        }

        // Envía una petición POST con el token en la cabecera
        fetch(ruta, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                // En caso de que necesites enviar más datos
                body: JSON.stringify({})
            })
            .then(response => {
                if (response.ok) {
                    // Redirige al usuario si la petición es exitosa
                    window.location.href = ruta;
                } else {
                    console.error('Error en la autenticación');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
</script>
<?php require_once './Views/templates/footer.php'; ?>