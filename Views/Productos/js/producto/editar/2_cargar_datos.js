const url = window.location.href;
let id = url.split('/').pop();
// si consta con # al final eliminarlo
if (id.includes('#')) {
    id = id.split('#')[0];
}
const buscar_datos_producto = async () => {
    const request = await impAxios(SERVERURL + '/productos/obtener_datos_producto/' + id);
    const data = request.data[0];

    let images = data.imagenes_adicionales;

    if (images != null) {
        images = JSON.parse(images)
        data.imagenes_adicionales = images
    }
    document.getElementById("codigo_producto").value= data.codigo_producto;
    document.getElementById("nombre_producto").value= data.nombre_producto;
    document.getElementById("descripcion").value= data.descripcion_producto;
    document.getElementById("pcp").value= Number.parseInt(data.costo_producto);
    document.getElementById("pvp").value= Number.parseInt(data.pvp);
}

buscar_datos_producto();