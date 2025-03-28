async function cargarDatosBodega() {
    const bodegaId = $('#id').val();
    const url = SERVERURL + 'Productos/obtenerBodega/' + bodegaId;

    const response = await impAxios(url);
    let data = response.data;
    if (data.length > 0) {
        const bodega = data[0];
        $("#nombre_bodega").val(bodega.nombre);
        setTimeout(() => {
            $("#provincia").val(bodega.provincia).trigger('change');
            cargarCiudades(bodega.provincia, bodega.localidad).then(() => {
                Swal.close()
            })
        }, 10)
        $("#direccion").val(bodega.direccion);
        $("#responsable").val(bodega.responsable);
        $("#num_casa").val(bodega.num_casa);
        $("#telefono").val(bodega.contacto);
        $("#referencia").val(bodega.referencia);
        if (bodega.global === "1") {
            $("#full").prop("checked", true);
            document.getElementById("full").checked ? document.getElementById("input-full").classList.remove("hidden-all") : document.getElementById("input-full").classList.add("hidden-all");
        }
        $("#valor_full").val(bodega.full_filme);

    }
}