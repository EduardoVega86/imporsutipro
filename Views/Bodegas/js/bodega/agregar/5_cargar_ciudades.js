const cargar_ciudades = async (codigo_provincia) => {
    const ciudadRequest = await impAxios(SERVERURL + "Ubicaciones/obtenerCiudades/" + codigo_provincia);
    const ciudades = ciudadRequest.data;

    const ciudadSelect = document.getElementById("ciudad");
    ciudadSelect.innerHTML = ""; // Limpiar opciones anteriores
    ciudades.forEach(ciudad => {
        const option = document.createElement("option");
        option.value = ciudad.id_cotizacion;
        option.textContent = ciudad.ciudad;
        ciudadSelect.appendChild(option);
    });
}
document.getElementById("provincia").addEventListener("change", async (e) => {
    const codigo_provincia = e.target.value;
    await cargar_ciudades(codigo_provincia);
    document.getElementById("ciudad").disabled = false;
})