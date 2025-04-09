const cargar_provincias = async () => {
    const provinciaRequest = await impAxios(SERVERURL + "Ubicaciones/obtenerProvincias").then((res) => {
        return res;
    })
    const provincias = provinciaRequest.data;
    const id_pais = "1";
    const provinciasFiltradas = provincias.filter(provincia => provincia.id_pais === id_pais);

    const ciudadSelect = document.getElementById("provincia");
    provinciasFiltradas.forEach(provincia => {
        const option = document.createElement("option");
        option.value = provincia.codigo_provincia;
        option.textContent = provincia.provincia;
        ciudadSelect.appendChild(option);
    });

}
cargar_provincias()