<?php
// Configuración de conexión a la base de datos
const HOST = '3.233.119.65';
const USER = "imporsuit_system";
const PASSWORD = "imporsuit_system";
const DB = "imporsuitpro_new";
const CHARSET = "utf8mb4";

// Ruta para el archivo de bloqueo
$lockFile = __DIR__ . '/cron_wait.lock';

// Ruta del archivo de log
$logFile = __DIR__ . '/error_log_wait.txt';

// Función para registrar errores en el archivo de log
function logError($message)
{
    global $logFile;
    $timestamp = date("Y-m-d H:i:s");
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// Función para conectar a la base de datos
function connectDatabase()
{
    $conn = new mysqli(HOST, USER, PASSWORD, DB);
    if ($conn->connect_error) {
        logError("Error de conexión a la base de datos: " . $conn->connect_error);
        die("Error de conexión a la base de datos.");
    }
    $conn->set_charset(CHARSET);
    return $conn;
}

// Función principal: validarTiempo
function validarTiempo($conn)
{
    try {
        // Aquí puedes agregar tu lógica específica
        $sql = "SELECT * FROM mensajes_espera";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $estado = $row["estado"];
                
                 if ($estado == 1){

                 } else if (){

                 }
            }
        } else {
            logError("No hay registros pendientes.");
        }
    } catch (Exception $e) {
        logError("Error en validarTiempo: " . $e->getMessage());
    }
}

// Evitar ejecuciones simultáneas
if (file_exists($lockFile)) {
    logError("El proceso ya está en ejecución.");
    exit;
}

file_put_contents($lockFile, getmypid());

try {
    // Conectar a la base de datos
    $conn = connectDatabase();

    // Ejecutar la función principal
    validarTiempo($conn);

    // Cerrar la conexión
    $conn->close();
} catch (Exception $e) {
    logError("Error en el script: " . $e->getMessage());
} finally {
    // Eliminar el archivo de bloqueo
    unlink($lockFile);
}

logError("Ejecución completada.");
echo "Ejecución completada.\n";
