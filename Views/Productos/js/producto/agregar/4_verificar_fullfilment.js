const validar_fullfilment = async () => {
    const response  = await impAxios(SERVERURL + 'productos/validar_fullfilment');
    const data = await response.data;

    if(data.data === 1){

    }
}

validar_fullfilment()