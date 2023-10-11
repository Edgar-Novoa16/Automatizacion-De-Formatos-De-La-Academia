
<?php
require 'Conexion.php';
conexionbd();
$conn = conexionbd();

$nombre = $_POST["Nombre"];
$asunto = $_POST["Asunto"];
$n_telefono = $_POST["N_Telefono"];
$semestre = $_POST["Semestre"];
$correo_electronico = $_POST["Correo_Electronico"];
$n_control = $_POST["N_Control"];
$solicito = $_POST["Solicito"];
$motivo = $_POST["Motivo"];
$razon = $_POST["Razon"];

$mensaje = "";

$consulta_existencia = "SELECT * FROM from_carta_comite WHERE N_Control = '$n_control'";
$resultado = $conn->query($consulta_existencia);

if ($resultado->num_rows > 0) {
    $mensaje = "El número de control $n_control ya existe en la base de datos. Por favor, elige otro número de control.";
} else {
    $sql = "INSERT INTO from_carta_comite (N_Control, Nombre, Asunto, N_Telefono, Semestre, Correo_Electronico, Solicito, Motivo, Razon, Fecha) 
    VALUES ('$n_control', '$nombre', '$asunto', $n_telefono, $semestre, '$correo_electronico', '$solicito', '$motivo', '$razon', CURDATE())";

    if ($conn->query($sql) === TRUE) {
        $mensaje = "Datos insertados correctamente";
    } else {
        $mensaje = "Error al insertar datos: " . $conn->error;
    }
}


?>


<!DOCTYPE html>
<html lang="es">
<body>
    <script>
        // Función para mostrar el mensaje emergente y volver a la página anterior
        function mostrarMensaje(mensaje) {
            alert(mensaje);
            window.history.back();
           /* if (confirm("¿Deseas descargar el documento generado?")) {
                // Iniciar la descarga del archivo
                window.location.href = "DescargaComite.php";
            } else {
            // Regresa a la página anterior en el historial del navegador
          
        }*/
    }
        // Llama a la función para mostrar el mensaje
        mostrarMensaje("<?php echo $mensaje; ?>");
    </script>
</body>
</html>

