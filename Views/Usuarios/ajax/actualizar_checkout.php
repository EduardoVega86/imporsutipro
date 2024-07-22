<?php
$data = file_get_contents("php://input");
if (!empty($data)) {
    $decodedData = json_decode($data, true);
    $id_plataforma = $decodedData['id_plataforma'];
    $items = $decodedData['items'];

    // Puedes hacer algo con $id_plataforma aquí si es necesario

    // Reemplaza '../json/checkout.json' con la ruta correcta al archivo JSON en tu servidor
    if (file_put_contents(SERVERURL.'Models/modales/'. $id_plataforma .'_modal.json', json_encode($items))) {
        echo "Estado guardado correctamente. ID Plataforma: $id_plataforma";
    } else {
        header("HTTP/1.1 500 Internal Server Error");
        echo "Error al guardar el estado";
    }
} else {
    header("HTTP/1.1 400 Bad Request");
    echo "No hay datos recibidos";
}
?>
