const listBodegas = async () => {
    try {
        const response = await impAxios(SERVERURL + "productos/cargarBodegas");
        const bodegas = await response.data;

        let content = ``;
        const ciudadPromises = bodegas.map((bodega) => cargarCiudad(bodega.localidad));

        // Esperar a que todas las promesas se resuelvan
        const ciudades = await Promise.all(ciudadPromises);

        bodegas.forEach((bodega, index) => {
            const ciudad = ciudades[index];

            let editar = ``;

            if (bodega.id_plataforma === ID_PLATAFORMA) {
                editar = `<li><span class="dropdown-item" style="cursor: pointer;" onclick="editar_bodegas(${bodega.id})"><i class="fa-solid fa-pencil"></i>Editar</span></li>
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="eliminarBodega(${bodega.id})"><i class="fa-solid fa-trash-can"></i>Eliminar</span></li>`;
            }
            const contenidoCiudad = MATRIZ == 11 ? bodega.localidad : ciudad;
            content += `
                <tr>
                    <td>${bodega.id}</td>
                    <td>${bodega.nombre}</td>
                    <td>${bodega.direccion}</td>
                    <td>${contenidoCiudad}</td>
                    <td>${bodega.responsable}</td>
                    <td>${bodega.contacto}</td>
                    <td>
                    <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><span class="dropdown-item" style="cursor: pointer;" onclick="ver_inventario(${bodega.id})"><i class='bx bxs-file-find'></i>Ver Inventario</span></li>
                        ${editar}
                    </ul>
                    </div>
                    </td>
                </tr>`;
        });
        document.getElementById("tableBody_bodegas").innerHTML = content;
    } catch (ex) {
        alert(ex);
    }
};
