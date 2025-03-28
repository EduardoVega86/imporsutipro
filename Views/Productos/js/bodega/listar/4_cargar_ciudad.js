async function cargarCiudad(id_ciudad) {
    const url = "" + SERVERURL + "Ubicaciones/obtenerCiudad/" + id_ciudad;
    try {
        const response = await fetch(url);
        const data = await response.json();
        return data[0].ciudad;
    } catch (error) {
        console.error("Error:", error);
        return null;
    }
}