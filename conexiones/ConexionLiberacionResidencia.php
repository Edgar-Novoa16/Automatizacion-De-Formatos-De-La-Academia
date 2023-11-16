
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

$nombre = mb_strtoupper($_POST["Nombre"]);
$apellido_paterno = mb_strtoupper($_POST['Apellido_Paterno']);
$apellido_materno = mb_strtoupper($_POST['Apellido_Materno']);
$n_control = mb_strtoupper($_POST["N_Control"]);
$periodo = mb_strtoupper ($_POST["Periodo"]);
$nombre_proyecto = mb_strtoupper ($_POST["Nombre_Proyecto"]);
$nombre_empresa = mb_strtoupper ($_POST["Nombre_Empresa"]);
$asesor_interno = mb_strtoupper ($_POST["Asesor_Interno"]);
$primer_docente = mb_strtoupper ($_POST["Primer_Docente"]);
$segundo_docente = mb_strtoupper ($_POST["Segundo_Docente"]);
$calificacion = mb_strtoupper ($_POST["Calificacion"]);
$observaciones =  ($_POST["Observaciones"]);
$evidencias = ($_POST["Evidencias"]);


$mensaje = ""; 
$mensajeexito = "";

// Verificar si el N_Control ya existe
$verificarExistencia = "SELECT N_Control FROM from_liberacion_residencia WHERE N_Control = '$n_control'";
$resultado = $conn->query($verificarExistencia);

if ($resultado->num_rows > 0) {
    // El N_Control ya existe, mostrar un mensaje de error o redireccionar a otra página
    echo "<script>
            window.onload = function() {
                alert('El número de control $n_control ya existe en la base de datos. Por favor, elige otro número de control.');
                window.history.back();
            }
          </script>";
} else {
$sql = "INSERT INTO from_liberacion_residencia (N_Control, Nombre, Apellido_Paterno, Apellido_Materno, Periodo, Nombre_Proyecto, Nombre_Empresa, Asesor_Interno, Primer_Docente, Segundo_Docente, Calificacion, Observaciones, Evidencias, Fecha) 
VALUES ('$n_control', '$nombre', '$apellido_paterno', '$apellido_materno','$periodo', '$nombre_proyecto', '$nombre_empresa', '$asesor_interno', '$primer_docente', '$segundo_docente', '$calificacion', '$observaciones', '$evidencias', CURDATE())";


    if ($conn->query($sql) === TRUE) {
        

        // Crear un nuevo objeto PHPWord
        $plantilla = '../plantillas\ANEXO XXXIII.FORMATO DE LIBERACIÓN DE PROYECTO PARA LA TITULACIÓN INTEGRAL.docx';
    
        // Crear un nuevo procesador de plantillas con la plantilla existente
        $templateProcessor = new TemplateProcessor($plantilla);
       // Establecer la configuración regional a español (o el idioma que prefieras)
       setlocale(LC_TIME, 'es_ES.utf8', 'es_ES', 'esp');
        // Obtener la fecha actual en el formato deseado 
        $fechaActual = strftime ('%e de %B de %Y');
        // Reemplazar las variables en la plantilla con los datos del formulario
        $templateProcessor->setValue('Nombre', $nombre);
        $templateProcessor->setValue('ApellidoP', $apellido_paterno);
        $templateProcessor->setValue('ApellidoM', $apellido_materno);
        $templateProcessor->setValue('Nom_Proyecto', $nombre_proyecto);
        $templateProcessor->setValue('N_Control', $n_control);
        $templateProcessor->setValue('Asesor_Interno', $asesor_interno);
        $templateProcessor->setValue('Revisor1', $primer_docente);
        $templateProcessor->setValue('Revisor2', $segundo_docente);
        $templateProcessor->setValue('Fecha', $fechaActual);
        
       
// Guardar el documento temporalmente
$tempFilePath = "{$apellido_paterno}_{$apellido_materno}_{$nombre}_{$n_control}_LIBERACIONRESIDENCIA.docx";

$templateProcessor->saveAs($tempFilePath);

 // Leer el contenido del archivo
 $fileContent = file_get_contents($tempFilePath);

 // Guardar el contenido del archivo en la base de datos
 $sqlUpdate = "UPDATE from_liberacion_residencia SET Constancia = ? WHERE N_Control = ?"; 
 $stmt = $conn->prepare($sqlUpdate);
 $stmt->bind_param("si", $fileContent, $n_control); 
 if ($stmt->execute()) {
    echo "<script>
    window.onload = function() {
        alert('¡Constancia generada y enviada a la division de ingenieria en sistemas con éxito!, Favor de pasar por ella al departamento');
        window.location.href = '../contenido/FormularioSolicitudLiberacionRecidencia.html';
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
       $mail->addAddress('edgnov16@gmail.com');
       $mail->Subject = 'Liberacion Residencia';
       $mail->Body = 'Carta del Alumno_ ' . $apellido_paterno . '_' . $apellido_materno . '_' . $nombre . 'Con Numero de Control_' . $n_control . '.';     $mail->addAttachment($tempFilePath, basename($tempFilePath));
   
       // Enviar el correo electrónico
       $mail->send();
   
       // Eliminar el archivo temporal después de enviarlo
       unlink($tempFilePath);
   
       
       return ;
   } catch (Exception $e) {
       return 'Error al enviar el correo: ' . $mail->ErrorInfo;
   }
   

}

   
?>














