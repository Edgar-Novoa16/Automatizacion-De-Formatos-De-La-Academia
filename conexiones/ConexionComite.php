<?php
// Adjuntar el archivo al correo electrónico
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

require 'Conexion.php';
require '../vendor/autoload.php'; // Incluye la autoloader de Composer
use PhpOffice\PhpWord\TemplateProcessor;
// Configura la zona horaria a Guadalajara, México
date_default_timezone_set('America/Mexico_City');

$nombre = mb_strtoupper ($_POST["Nombre"]);
$apellido_paterno = mb_strtoupper ($_POST['Apellido_Paterno']);
$apellido_materno = mb_strtoupper($_POST['Apellido_Materno']);
$asunto = mb_strtoupper($_POST['Asunto']);
$otroasunto = mb_strtoupper ($_POST["OtroAsunto"]);

if ($asunto === "OTRO") {
    $asunto = $otroasunto;
}
$n_telefono = mb_strtoupper($_POST["N_Telefono"]);
$semestre = mb_strtoupper($_POST["Semestre"]);
$correo_electronico = ($_POST["Correo_Electronico"]);
$n_control = mb_strtoupper($_POST["N_Control"]);
$solicito = mb_strtoupper($_POST["Solicito"]);
$motivo = mb_strtoupper($_POST['Motivo']);
$razon = ($_POST["Razon"]);
 



$mensaje = ""; 
$mensajeexito = "";


    $sql = "INSERT INTO from_carta_comite (N_Control, Nombre, Apellido_Paterno, Apellido_Materno, Asunto, N_Telefono, Semestre, Correo_Electronico, Solicito, Motivo, Razon, Fecha) 
    VALUES ('$n_control', '$nombre', '$apellido_paterno', '$apellido_materno','$asunto', '$n_telefono', '$semestre', '$correo_electronico', '$solicito', '$motivo', '$razon', CURDATE())";
    if ($conn->query($sql) === TRUE) {
        

   
            // Crear un nuevo objeto PHPWord
            $plantilla = '../plantillas\ANEXO XLII. SOLICITUD COMITE.docx';
        
            // Crear un nuevo procesador de plantillas con la plantilla existente
            $templateProcessor = new TemplateProcessor($plantilla);
            // Establecer la configuración regional a español (o el idioma que prefieras)
            setlocale(LC_TIME, 'es_ES.utf8', 'es_ES', 'esp');
            // Obtener la fecha actual en el formato deseado 
            $fechaActual = strftime ('%e de %B de %Y');
            // Reemplazar las variables en la plantilla con los datos del formulario
            $templateProcessor->setValue('Nombre', $nombre);
            $templateProcessor->setValue('Apellido_Paterno', $apellido_paterno);
            $templateProcessor->setValue('Apellido_Materno', $apellido_materno);
            $templateProcessor->setValue('Asunto', $asunto);
            $templateProcessor->setValue('N_Telefono', $n_telefono);
            $templateProcessor->setValue('Semestre', $semestre);
            $templateProcessor->setValue('Correo_Electronico', $correo_electronico);
            $templateProcessor->setValue('N_Control', $n_control);
            $templateProcessor->setValue('Solicito', $solicito);
            $templateProcessor->setValue('Motivo', $motivo);
            $templateProcessor->setValue('Razon', $razon);
            $templateProcessor->setValue('Fecha', $fechaActual);
    
   
        
       
// Guardar el documento temporalmente
$tempFilePath = "{$n_control}_{$apellido_paterno}_{$apellido_materno}_{$nombre}_CONSTANCIACOMITE.docx";

$templateProcessor->saveAs($tempFilePath);

 // Leer el contenido del archivo
 $fileContent = file_get_contents($tempFilePath);

 // Guardar el contenido del archivo en la base de datos
 $sqlUpdate = "UPDATE from_carta_comite SET Constancia = ? WHERE N_Control = ?"; 
 $stmt = $conn->prepare($sqlUpdate);
 $stmt->bind_param("si", $fileContent, $n_control); 
 if ($stmt->execute()) {
    echo "<script>
    window.onload = function() {
        alert('¡Carta generada y enviada al correo que compartiste con éxito!, Favor de firmarla y subirla en el apartado de formularios');
        window.location.href = '../contenido/FormularioCartaComite.html';
    }
  </script>";
} else {
    echo "Error al insertar datos y guardar el archivo: " . $conn->error;
}
         $stmt->close();
 }


   
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
       $mail->addAddress($correo_electronico);
       $mail->Subject = 'Carta Comite';
       $mail->Body = 'Hola buen dia  ' . $apellido_paterno . '_' . $apellido_materno . '_' . $nombre . '_' .'Con Numero de Control_' . $n_control . '.' .'Favor de imprimirla, firmarla subirla aqui http://localhost/Automatizacion-De-Formatos-De-La-Academia/contenido/ComiteFirmada.html' ;     $mail->addAttachment($tempFilePath, basename($tempFilePath));
   
       // Enviar el correo electrónico
       $mail->send();
   
       // Eliminar el archivo temporal después de enviarlo
       unlink($tempFilePath);
   
       
       return ;
   } catch (Exception $e) {
       return 'Error al enviar el correo: ' . $mail->ErrorInfo;
   }
   



   
?>


