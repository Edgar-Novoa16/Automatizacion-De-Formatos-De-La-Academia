<?php
function conexionbd() {
    $servername = "localhost";
    $username = "root";
    $password = "12345678";
    $database = "automatizacion_de_formatos_de_la_academia";

    // Crear una nueva conexión
    $conn = new mysqli($servername, $username, $password, $database);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Devolver el objeto de conexión
    return $conn;
}

?>