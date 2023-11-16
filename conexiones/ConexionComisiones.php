<?php

require 'Conexion.php';
require '../vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Configura la zona horaria a Guadalajara, México
date_default_timezone_set('America/Mexico_City');

function obtenerDatosFormulario() {
    return [
        'Nombre' => mb_strtoupper($_POST["Nombre"]),
        'Apellido_Paterno' => mb_strtoupper($_POST['Apellido_Paterno']),
        'Apellido_Materno' => mb_strtoupper($_POST['Apellido_Materno']),
        'Nomina' => mb_strtoupper($_POST["Nomina"]),
        'Cargo' => obtenerCargo(),
        'Area' => obtenerArea(),
        'Lugar' => $_POST["Lugar"],
        'Asunto' => $_POST["Asunto"],
        'Transporte' => mb_strtoupper($_POST["Transporte"]),
        'Viaticos' => mb_strtoupper($_POST["Viaticos"]),
        'Especificacion_Viaticos' => obtenerEspecificacionViaticos(),
        'Observaciones' => $_POST["Observaciones"],
        'Dia_Salida' => $_POST["Dia_Salida"],
        'Hora_Salida' => $_POST["Hora_Salida"],
        'Dia_Regreso' => $_POST["Dia_Regreso"],
        'Hora_Regreso' => $_POST["Hora_Regreso"],
    ];
}

function obtenerCargo() {
    $cargo = mb_strtoupper($_POST["Cargo"]);
    $otrocargo = mb_strtoupper($_POST["OtroCargo"]);
    return ($cargo === "OTRO") ? $otrocargo : $cargo;
}

function obtenerArea() {
    $area = mb_strtoupper($_POST["Area"]);
    $otroarea = mb_strtoupper($_POST["OtroArea"]);
    return ($area === "OTRO") ? $otroarea : $area;
}

function obtenerEspecificacionViaticos() {
    $especificacion_viaticos = mb_strtoupper($_POST["Especificacion_Viaticos"]);
    $otroespecificacion_viaticos = mb_strtoupper($_POST["OtroEspecificacion_Viaticos"]);
    return ($especificacion_viaticos === "OTRO") ? $otroespecificacion_viaticos : $especificacion_viaticos;
}

function insertarDatosBD($datos) {
    global $conn;

    $sql = "INSERT INTO from_comisiones (Nomina, Nombre, Apellido_Paterno, Apellido_Materno, Cargo, Area, Lugar, Asunto, Transporte, Viaticos, Especificacion_Viaticos, Observaciones, Dia_Salida, Hora_Salida, Dia_Regreso, Hora_Regreso, Fecha) 
    VALUES ('{$datos['Nomina']}', '{$datos['Nombre']}', '{$datos['Apellido_Paterno']}', '{$datos['Apellido_Materno']}', '{$datos['Cargo']}', '{$datos['Area']}', '{$datos['Lugar']}', '{$datos['Asunto']}', '{$datos['Transporte']}', '{$datos['Viaticos']}', '{$datos['Especificacion_Viaticos']}', '{$datos['Observaciones']}', '{$datos['Dia_Salida']}', '{$datos['Hora_Salida']}', '{$datos['Dia_Regreso']}', '{$datos['Hora_Regreso']}', CURDATE())";

    return $conn->query($sql);
}

function generarDocumento($datos) {
    $plantilla = '../plantillas/FormatoComision.docx';
    $templateProcessor = new TemplateProcessor($plantilla);
    setlocale(LC_TIME, 'es_ES.utf8', 'es_ES', 'esp');
    $fechaActual = strftime('%e de %B de %Y');
    $folio = $GLOBALS['conn']->insert_id;

    $templateProcessor->setValue('Nomina', $datos['Nomina']);
    $templateProcessor->setValue('Nombre', $datos['Nombre']);
    $templateProcessor->setValue('ApellidoP', $datos['Apellido_Paterno']);
    $templateProcessor->setValue('ApellidoM', $datos['Apellido_Materno']);
    $templateProcessor->setValue('Cargo', $datos['Cargo']);
    $templateProcessor->setValue('Folio', $folio);
    $templateProcessor->setValue('Area', $datos['Area']);
    $templateProcessor->setValue('Lugar', $datos['Lugar']);
    $templateProcessor->setValue('Asunto', $datos['Asunto']);
    $templateProcessor->setValue('Transporte', $datos['Transporte']);
    $templateProcessor->setValue('Viaticos', $datos['Viaticos']);
    $templateProcessor->setValue('Especificacion', $datos['Especificacion_Viaticos']);
    $templateProcessor->setValue('Obs', $datos['Observaciones']);
    $templateProcessor->setValue('Dia_Salida', $datos['Dia_Salida']);
    $templateProcessor->setValue('Hora_Salida', $datos['Hora_Salida']);
    $templateProcessor->setValue('Dia_Regreso', $datos['Dia_Regreso']);
    $templateProcessor->setValue('Hora_Regreso', $datos['Hora_Regreso']);
    $templateProcessor->setValue('Fecha', $fechaActual);

    $tempFilePath = "{$datos['Apellido_Paterno']}_{$datos['Apellido_Materno']}_{$datos['Nombre']}_{$datos['Nomina']}_COMISION.docx";
    $templateProcessor->saveAs($tempFilePath);

    return $tempFilePath;
}

function guardarDocumentoBD($tempFilePath) {
    $fileContent = file_get_contents($tempFilePath);
    $folio = $GLOBALS['conn']->insert_id;

    $sqlUpdate = "UPDATE from_comisiones SET Comision = ? WHERE Folio = ?";
    $stmt = $GLOBALS['conn']->prepare($sqlUpdate);
    $stmt->bind_param("si", $fileContent, $folio);

    return $stmt->execute();
}

function enviarCorreo($tempFilePath, $apellido_paterno, $apellido_materno, $nombre, $nomina) {
    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'edgnov16@gmail.com';
        $mail->Password = 'jkssufflhwbrjksq';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('edgnov16@gmail.com', 'Division Ingenieria en Sistemas Computacionales');
        $mail->addAddress('edgnov16@gmail.com');
        $mail->Subject = 'Comision';
        $mail->Body = 'Comisión del docente_ ' . $apellido_paterno . '_' . $apellido_materno . '_' . $nombre . ' con Nomina_' . $nomina . '.';
        $mail->addAttachment($tempFilePath, basename($tempFilePath));

        $mail->send();

        unlink($tempFilePath);
        echo "<script>alert('¡Comisión generada y enviada a su jefe de carrera con éxito!');
        window.location.href = '../contenido/FormularioComisionesDocentes.html';</script>";
    } catch (Exception $e) {
        return 'Error al enviar el correo: ' . $mail->ErrorInfo;
    }
}


// Main
$mensaje = "";
$mensajeexito = "";

$datosFormulario = obtenerDatosFormulario();

if (insertarDatosBD($datosFormulario)) {
    $tempFilePath = generarDocumento($datosFormulario);

    if (guardarDocumentoBD($tempFilePath)) {
        $resultadoEnvio = enviarCorreo($tempFilePath, $datosFormulario['Apellido_Paterno'], $datosFormulario['Apellido_Materno'], $datosFormulario['Nombre'], $datosFormulario['Nomina']);

        if (strpos($resultadoEnvio, 'Error') === false) {
            // Éxito
            echo $resultadoEnvio;
        } else {
            // Error
            echo $resultadoEnvio;
        }
    } else {
        $mensaje = "Error al insertar datos y guardar el archivo: " . $conn->error;
    }
} else {
    $mensaje = "Error al insertar datos: " . $conn->error;
}


?>



<!DOCTYPE html>
<html lang="es">
<body>
    <script>
        // Función para mostrar el mensaje emergente y redirigir después de un tiempo
        function mostrarMensaje(mensajeexito) {
            alert(mensajeexito);

            // Redirigir a FormularioCartaComite.html inmediatamente si el número de control ya existe
            window.location.href = "../contenido/FormularioComisionesDocentes.html";
        }

        // Llamar a la función para mostrar el mensaje
        mostrarMensaje("<?php echo $mensajeexito; ?>");

      
    </script>
</body>
</html>

