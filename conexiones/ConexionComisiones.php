

<?php
require 'Conexion.php';
require '../vendor/autoload.php'; // Incluye la autoloader de Composer
use PhpOffice\PhpWord\TemplateProcessor;
// Configura la zona horaria a Guadalajara, México
date_default_timezone_set('America/Mexico_City');


$nombre =  mb_strtoupper($_POST["Nombre"]);
$apellido_paterno =  mb_strtoupper($_POST['Apellido_Paterno']);
$apellido_materno =  mb_strtoupper($_POST['Apellido_Materno']);
$nomina =  mb_strtoupper($_POST["Nomina"]);
$cargo =  mb_strtoupper ($_POST["Cargo"]);
$otrocargo =  mb_strtoupper ($_POST["OtroCargo"]);

if ($cargo === "OTRO") {
    $cargo = $otrocargo;
}
$area =  mb_strtoupper ($_POST["Area"]);
$otroarea =  mb_strtoupper ($_POST["OtroArea"]);

if ($area === "OTRO") {
    $area = $otroarea;
}
$lugar =  ($_POST["Lugar"]);
$asunto =  ($_POST["Asunto"]);
$transporte =  mb_strtoupper ($_POST["Transporte"]);
$viaticos =  mb_strtoupper ($_POST["Viaticos"]);
$especificacion_viaticos = mb_strtoupper ($_POST["Especificacion_Viaticos"]);
$otroespecificacion_viaticos = mb_strtoupper($_POST["OtroEspecificacion_Viaticos"]);

if ($especificacion_viaticos === "OTRO") {
    $especificacion_viaticos = $otroespecificacion_viaticos;
}

$observaciones =  ($_POST["Observaciones"]);
$dia_salida =  ($_POST["Dia_Salida"]);
$hora_salida = ($_POST["Hora_Salida"]);
$dia_regreso = ($_POST["Dia_Regreso"]);
$hora_regreso = ($_POST["Hora_Regreso"]);



$mensaje = ""; 
$mensajeexito = "";

$sql = "INSERT INTO from_comisiones (Nomina, Nombre, Apellido_Paterno, Apellido_Materno, Cargo, Area, Lugar, Asunto, Transporte, Viaticos, Especificacion_Viaticos, Observaciones, Dia_Salida, Hora_Salida, Dia_Regreso, Hora_Regreso, Fecha) 

    VALUES ('$nomina', '$nombre', '$apellido_paterno', '$apellido_materno','$cargo', '$area', '$lugar', '$asunto', '$transporte', '$viaticos', '$especificacion_viaticos', '$observaciones', '$dia_salida', '$hora_salida', '$dia_regreso', ' $hora_regreso', CURDATE())";

    if ($conn->query($sql) === TRUE) {
        $mensajeexito = "Datos insertados correctamente";

        // Crear un nuevo objeto PHPWord
        $plantilla = '../plantillas/FormatoComision.docx';
    
        // Crear un nuevo procesador de plantillas con la plantilla existente
        $templateProcessor = new TemplateProcessor($plantilla);
       // Establecer la configuración regional a español (o el idioma que prefieras)
       setlocale(LC_TIME, 'es_ES.utf8', 'es_ES', 'esp');
        // Obtener la fecha actual en el formato deseado 
        $fechaActual = strftime ('%e de %B de %Y');

          // Obtén el último ID insertado (Folio) después de la inserción
        $folio = $conn->insert_id;
        // Reemplazar las variables en la plantilla con los datos del formulario
        $templateProcessor->setValue('Nomina', $nomina);
        $templateProcessor->setValue('Nombre', $nombre);
        $templateProcessor->setValue('ApellidoP', $apellido_paterno);
        $templateProcessor->setValue('ApellidoM', $apellido_materno);
        $templateProcessor->setValue('Cargo', $cargo);
        $templateProcessor->setValue('Folio', $folio);
        $templateProcessor->setValue('Area', $area);
        $templateProcessor->setValue('Lugar', $lugar);
        $templateProcessor->setValue('Asunto', $asunto);
        $templateProcessor->setValue('Transporte', $transporte);
        $templateProcessor->setValue('Viaticos', $viaticos);
        $templateProcessor->setValue('Especificacion', $especificacion_viaticos);
        $templateProcessor->setValue('Obs', $observaciones);
        $templateProcessor->setValue('Dia_Salida', $dia_salida);
        $templateProcessor->setValue('Hora_Salida', $hora_salida);
        $templateProcessor->setValue('Dia_Regreso', $dia_regreso);
        $templateProcessor->setValue('Hora_Regreso', $hora_regreso);
        $templateProcessor->setValue('Fecha', $fechaActual);
        
       
        // Guardar el documento temporalmente
    $tempFilePath = "{$apellido_paterno}_{$apellido_materno}_{$nombre}_{$nomina}_COMISION.docx";

    $templateProcessor->saveAs($tempFilePath);

         // Leer el contenido del archivo
         $fileContent = file_get_contents($tempFilePath);
    
         // Guardar el contenido del archivo en la base de datos
         $sqlUpdate = "UPDATE from_comisiones SET Comision = ? WHERE Folio = ?"; 
         $stmt = $conn->prepare($sqlUpdate);
         $stmt->bind_param("si", $fileContent, $folio); 
         if ($stmt->execute()) {
             $mensaje = "¡Comision generada y guardada con éxito!";
         } else {
             $mensaje = "Error al insertar datos y guardar el archivo: " . $conn->error;
         }
         $stmt->close();
 }

// Adjuntar el archivo al correo electrónico
   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\SMTP;
   use PHPMailer\PHPMailer\Exception;
   
   require '../vendor/phpmailer/phpmailer/src/Exception.php';
   require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
   require '../vendor/phpmailer/phpmailer/src/SMTP.php';
   
   try {
       $mail = new PHPMailer(true);
   
       // Configurar el servidor SMTP de Gmail
       $mail->isSMTP();
       $mail->Host = 'smtp.gmail.com';
       $mail->SMTPAuth = true;
       $mail->Username = 'edgnov16@gmail.com';
       $mail->Password = 'jkssufflhwbrjksq ';
       $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
       $mail->Port = 587;
   
       // Configurar el correo electrónico
      
       $mail->setFrom('edgnov16@gmail.com', 'Division Ingeneria en Sistemas Computacionales');
       $mail->addAddress('edgnov16@gmail.com');
       $mail->Subject = 'Comision';
       $mail->Body = 'Comisión del docente_ ' . $apellido_paterno . '_' . $apellido_materno . '_' . $nombre . 'con Nomina_' . $nomina . '.';       $mail->addAttachment($tempFilePath, basename($tempFilePath));
   
       // Enviar el correo electrónico
       $mail->send();
   
       // Eliminar el archivo temporal después de enviarlo
       unlink($tempFilePath);
   
       // Mostrar el mensaje de éxito
       echo "<script>alert('¡Comisión generada y enviada a su jefe de carrera con éxito!');
       window.location.href = '../contenido/FormularioComisionesDocentes.html';</script>";
       } catch (Exception $e) {
       echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
   }
   



   
?>





