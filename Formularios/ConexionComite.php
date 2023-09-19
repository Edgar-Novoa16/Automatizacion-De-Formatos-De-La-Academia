<?php
// Conexión a la base de datos (ajusta los valores según tu configuración)
$servername = "localhost";

$username = "root";
$password = "12345678";
$database = "automatizacion_de_formatos_de_la_academia";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recuperar datos del formulario
$nombre = $_POST["Nombre"];
$asunto = $_POST["Asunto"];
$n_telefono = $_POST["N_Telefono"];
$semestre = $_POST["Semestre"];
$correoElectronico = $_POST["Correo_Electrónico"];
$n_control = $_POST["N_Control"];
$solicito = $_POST["Solicito"];
$motivo = $_POST["Motivo"];
$razon = $_POST["Razon"];

// Consulta SQL para insertar los datos en la base de datos
$sql = "INSERT INTO from_carta_comite (N_Control, Nombre, Asunto, N_Telefono, Semestre, Correo_Electronico, Solicito, Motivo, Razon) 
VALUES ('$n_control', '$nombre', '$asunto', $n_telefono, $semestre, '$correoElectronico', '$solicito', '$motivo', '$razon')";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    echo "Datos insertados correctamente";
} else {
    echo "Error al insertar datos: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
