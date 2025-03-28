const verificar_proveeduria = async () => {
    const proveeduriaResponse = await impAxios(SERVERURL + "Usuarios/obtenerProveeduria/" + ID_PLATAFORMA);
    const proveeduria = proveeduriaResponse.data;
    if (proveeduria.data === 1) {
        document.getElementById("menu_full").classList.remove("hidden-all");
    }
}

verificar_proveeduria()