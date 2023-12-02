<?php
require 'Conexion.php';

// Obtener la nómina del formulario
$nomina = $_POST['Nomina'];

// Consulta SQL para obtener la información del profesor con la nómina proporcionada
$sql = "SELECT * FROM docentes WHERE Nomina = '$nomina'";
$result = $conn->query($sql);



if ($result->num_rows > 0) {
    // Obtener la información del docente
    $row = $result->fetch_assoc();
    $apellido_paterno = $row['Apellido_Paterno'];
    $apellido_materno = $row['Apellido_Materno'];
    $nombre = $row['Nombre'];
    $cargo = $row['Cargo'];
    $area = $row['Area'];

    $apellido_paterno = '';
    $apellido_materno = '';
    $nombre = "";
    $nomina ="";
    $cargo ="";
    $area = "";

    // Enviar la respuesta como JSON
    echo json_encode(array('success' => true, 'data' => $data));
} else {
    // Enviar un mensaje de error si no se encontró información para la nómina proporcionada
    echo json_encode(array('success' => false, 'message' => 'No se encontró información para la nómina proporcionada.'));
}

// Cerrar la conexión a la base de datos
$conn->close();
?>