<?php

class Logger {
    private mixed $logFile;

    public function __construct($logFile = "logs/app.log") {
        $this->logFile = $logFile;
        // Crear el directorio de logs si no existe
        if (!file_exists(dirname($logFile))) {
            mkdir(dirname($logFile), 0777, true);
        }
    }

    public function log($message, $level = 'ERROR'): void
    {
        $date = date('Y-m-d H:i:s');
        $logMessage = "[$date][$level] $message" . PHP_EOL;
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }
}

