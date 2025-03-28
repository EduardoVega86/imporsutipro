function eliminarBodega(id) {
    let formData = new FormData();
    formData.append("id", id); // Añadir el SKU al FormData

    $.ajax({
        type: "POST",
        url: SERVERURL + "productos/eliminarBodega",
        data: formData,
        processData: false, // No procesar los datos
        contentType: false, // No establecer ningún tipo de contenido
        success: function (response) {
            response = JSON.parse(response);
            // Mostrar alerta de éxito
            if (response.status === 500) {
                Swal.fire({
                    icon: "error",
                    title: response.title,
                    text: response.message,
                });
            } else {
                Swal.fire({
                    icon: "success",
                    title: response.title,
                    text: response.message,
                    showConfirmButton: false,
                    timer: 2000,
                }).then(() => {
                    // Recargar la DataTable
                    initDataTable();
                });
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX:", error);
            alert("Hubo un problema al eliminar la bodega");
        },
    });
}
