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
$n_control = ($_POST["N_Control"]);
$semestre = mb_strtoupper ($_POST["Semestre"]);
$otrosemestre = mb_strtoupper ($_POST["OtroSemestre"]);

// Si el valor del semestre es "otro", usa el valor de $otroSemestre
if ($semestre === "OTRO") {
    $semestre = $otrosemestre;
}
$carrera = mb_strtoupper ($_POST["Carrera"]);
$plan_estudio = mb_strtoupper ($_POST["Plan_Estudio"]);
$instituto = mb_strtoupper ($_POST["Instituto"]);
$traslado_carrera = mb_strtoupper ($_POST["Traslado_Carrera"]);
$con_plan = mb_strtoupper ($_POST["Con_Plan"]);
$debido =  ($_POST["Debido"]);



$mensaje = ""; 
$mensajeexito = "";

// Verificar si el N_Control ya existe
$verificarExistencia = "SELECT N_Control FROM from_solicitud_traslado WHERE N_Control = '$n_control'";
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
    $sql = "INSERT INTO from_solicitud_traslado (N_Control, Nombre, Apellido_Paterno, Apellido_Materno, Semestre, Carrera, Plan_Estudio, Instituto, Traslado_Carrera, Con_Plan, Debido,  Fecha) 
    VALUES ('$n_control', '$nombre', '$apellido_paterno','$apellido_materno', '$semestre','$carrera', '$plan_estudio', '$instituto', '$traslado_carrera', '$con_plan', '$debido', CURDATE())";
    if ($conn->query($sql) === TRUE) {
        

     // Crear un nuevo objeto PHPWord
    $plantilla = '../plantillas/ANEXO II. SOLICITUD DE TRASLADO .docx';

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
    $templateProcessor->setValue('N_Control', $n_control);
    $templateProcessor->setValue('Semestre', $semestre);
    $templateProcessor->setValue('Carrera', $carrera);
    $templateProcessor->setValue('Plan_Estudio', $plan_estudio);
    $templateProcessor->setValue('Instituto', $instituto);
    $templateProcessor->setValue('Traslado_Carrera', $traslado_carrera);
    $templateProcessor->setValue('Con_Plan', $con_plan);
    $templateProcessor->setValue('Debido', $debido);
    $templateProcessor->setValue('Fecha', $fechaActual);
    
   
        
       
// Guardar el documento temporalmente
$tempFilePath = "{$apellido_paterno}_{$apellido_materno}_{$nombre}_{$n_control}_TRASLADO.docx";

$templateProcessor->saveAs($tempFilePath);

 // Leer el contenido del archivo
 $fileContent = file_get_contents($tempFilePath);

 // Guardar el contenido del archivo en la base de datos
 $sqlUpdate = "UPDATE from_solicitud_traslado SET Solicitud = ? WHERE N_Control = ?"; 
 $stmt = $conn->prepare($sqlUpdate);
 $stmt->bind_param("si", $fileContent, $n_control); 
 if ($stmt->execute()) {
    echo "<script>
    window.onload = function() {
        alert('¡Solicitud generada y enviada a la division de ingenieria en sistemas con éxito!, Favor de pasar por ella al departamento');
        window.location.href = '../contenido/FormularioSolicitudTraslado.html';
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
       $mail->Subject = 'Solicitud de Traslado';
       $mail->Body = 'Solicitud Traslado  Alumno_ ' . $apellido_paterno . '_' . $apellido_materno . '_' . $nombre . '_' .'Con Numero de Control_' . $n_control . '.';     $mail->addAttachment($tempFilePath, basename($tempFilePath));
   
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


