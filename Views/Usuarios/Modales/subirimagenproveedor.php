<!-- Modal para subir imagen -->
<div class="modal fade" id="modalSubirImagen" tabindex="-1" aria-labelledby="modalSubirImagenLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSubirImagenLabel">Subir Imagen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_plataforma_subir" />

                <div class="form-group">
                    <label for="fileImagenProveedor">Selecciona la imagen:</label>
                    <input
                        type="file"
                        class="form-control"
                        id="fileImagenProveedor"
                        accept="image/*" />
                </div>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal">
                    Cerrar
                </button>
                <button
                    type="button"
                    class="btn btn-primary"
                    onclick="enviarImagen()">
                    Subir
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    async function enviarImagen() {
        const id_plataforma = document.getElementById("id_plataforma_subir").value;
        const fileInput = document.getElementById("fileImagenProveedor"); // tu <input type="file">

        if (!fileInput.files.length) {
            alert("Por favor, selecciona una imagen antes de subir.");
            return;
        }

        const imagenFile = fileInput.files[0];
        let formData = new FormData();
        formData.append("id_plataforma", id_plataforma);
        formData.append("imagen", imagenFile);

        try {
            const response = await fetch(`${SERVERURL}usuarios/subir_foto_proveedor`, {
                method: "POST",
                body: formData,
            });
            const result = await response.json();

            if (result.status === 200) {
                alert(result.message || "Imagen subida correctamente.");
                $("#modalSubirImagen").modal("hide");
                // initDataTableListaUsuarioMatriz(); // Si quieres refrescar
            } else {
                alert(result.message || "Error al subir la imagen.");
            }
        } catch (error) {
            console.error("Error al subir la imagen:", error);
            alert("Ha ocurrido un error al subir la imagen.");
        }
    }
</script>