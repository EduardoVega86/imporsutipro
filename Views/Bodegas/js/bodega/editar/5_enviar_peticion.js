document.getElementById("frmBodega").addEventListener("submit", async function (event) {
    event.preventDefault();

    const data = {
        nombre_bodega: document.getElementById("nombre_bodega").value,
        provincia: document.getElementById("provincia").value,
        ciudad: document.getElementById("ciudad").value,
        direccion: document.getElementById("direccion").value,
        num_casa: document.getElementById("num_casa").value,
        responsable: document.getElementById("responsable").value,
        telefono: document.getElementById("telefono").value,
        referencia: document.getElementById("referencia").value,
        isFull: document.getElementById("full").checked,
        full: document.getElementById("valor_full").value,
        id: document.getElementById("id").value,
    }
    // mostrar loading
    Swal.fire({
        title: 'Cargando',
        html: 'Por favor espere...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
        showConfirmButton: false
    });

    const bodegaResponse = await impAxios.post(SERVERURL + "productos/editarBodega", data).then((response) => {
        if (response.data.status === 200) {
            Swal.fire({
                icon: 'success',
                title: 'Bodega actualizada',
                text: response.data.message,
                confirmButtonText: 'Aceptar',
                showCancelButton: false,
                showCloseButton: false,
                showConfirmButton: false,
                timer: 2000,
            }).then((result) => {

                window.location.href = SERVERURL + "productos/bodegas";

            })
        }

    }).catch((response) => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: response.message,
            showConfirmButton: true,
            confirmButtonText: 'Aceptar',
        })
    })
})
;