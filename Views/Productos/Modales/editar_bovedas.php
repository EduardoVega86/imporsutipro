<form id="formEditarBoveda">
    <div class="mb-3">
        <label for="editNombreBoveda" class="form-label">Nombre</label>
        <select id="editNombreBoveda" name="id_producto" class="form-select" required>
            <!-- Opciones dinámicas -->
        </select>
    </div>
    <div class="mb-3">
        <label for="editCategoriaBoveda" class="form-label">Categoría</label>
        <select id="editCategoriaBoveda" name="id_linea" class="form-select" required>
            <!-- Opciones dinámicas -->
        </select>
    </div>
    <div class="mb-3">
        <label for="editProveedorBoveda" class="form-label">Proveedor</label>
        <select id="editProveedorBoveda" name="id_plataforma" class="form-select" required>
            <!-- Opciones dinámicas -->
        </select>
    </div>
    <div class="mb-3">
        <label for="editEjemploLanding" class="form-label">Ejemplo Landing</label>
        <input type="url" class="form-control" id="editEjemploLanding" name="ejemplo_landing">
    </div>
    <div class="mb-3">
        <label for="editDuplicarFunnel" class="form-label">Duplicar Funnel</label>
        <input type="url" class="form-control" id="editDuplicarFunnel" name="duplicar_funnel">
    </div>
    <div class="mb-3">
        <label for="editVideosBoveda" class="form-label">Videos</label>
        <input type="url" class="form-control" id="editVideosBoveda" name="videos">
    </div>
    <div class="mb-3">
        <label for="editImagenBoveda" class="form-label">Imagen</label>
        <input type="file" class="form-control" id="editImagenBoveda" name="imagen" accept="image/*">
        <img id="edit-preview-imagen" src="#" alt="Vista previa de la imagen" style="display: none; margin-top: 10px; max-width: 100%;">
    </div>
    <button type="submit" class="btn btn-primary">Guardar cambios</button>
</form>