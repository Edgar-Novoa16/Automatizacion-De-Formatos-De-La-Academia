<?php
require 'Conexion.php';
require '../vendor/autoload.php'; // Incluye la autoloader de Composer
use PhpOffice\PhpWord\TemplateProcessor;
date_default_timezone_set('America/Mexico_City');
// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recupera los datos del formulario


    $nombre = mb_strtoupper($_POST["Nombre"]);
    $apellido_paterno = mb_strtoupper($_POST['Apellido_Paterno']);
    $apellido_materno = mb_strtoupper($_POST['Apellido_Materno']);
    $n_control = ($_POST["N_Control"]);
    $semestre = mb_strtoupper ($_POST["Semestre"]);
    $carrera = mb_strtoupper ($_POST["Carrera"]);
    $plan_estudio = mb_strtoupper ($_POST["Plan_Estudio"]);
    $instituto = mb_strtoupper ($_POST["Instituto"]);
    $traslado_carrera = mb_strtoupper ($_POST["Traslado_Carrera"]);
    $con_plan = mb_strtoupper ($_POST["Con_Plan"]);
    $debido =  ($_POST["Debido"]);
    

    // Actualiza los datos en la base de datos
    $sql = "UPDATE from_solicitud_traslado SET 
            Nombre = '$nombre',
            Apellido_Paterno = '$apellido_paterno',
            Apellido_Materno = '$apellido_materno',
            Semestre = '$semestre',
            Carrera = '$carrera',
            Plan_Estudio = '$plan_estudio',
            Instituto = ' $instituto',
            Traslado_Carrera = ' $traslado_carrera',
            Con_Plan = '$con_plan',
            Debido= '$debido'
            

            WHERE N_Control = $n_control";

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

/// Eliminar el archivo temporal después de obtener su contenido
unlink($tempFilePath);

// Actualizar el campo "Comision" en la tabla "from_comisiones"
$sqlUpdate = "UPDATE from_solicitud_traslado SET Solicitud = ? WHERE N_Control = ?";
$stmt = $conn->prepare($sqlUpdate);
$stmt->bind_param("si", $fileContent, $n_control);
$stmt->execute();


        echo "<script>alert('¡Constancia Modificada con Exito!');
        window.location.href = '../conexiones/ConsultarSolicitudTraslado.php';</script>";
    } else {
        echo "Error al actualizar los datos: " . $conn->error;
    }

   
}
$n_control = '';
$apellido_paterno = '';
$apellido_materno = '';
$nombre = "";
$semestre ="";
$carrera ="";
$plan_estudio = "";
$instituto ="";
$traslado_carrera = "";
$con_plan ="";
$debido ="";

// Recupera los datos existentes para llenar el formulario
if (isset($_GET['N_Control'])) {
    $n_control = $_GET['N_Control']; // Asegúrate de pasar el ID correctamente, por ejemplo, desde la URL
  
    // Consulta para obtener los datos existentes
    $sql = "SELECT * FROM from_solicitud_traslado WHERE N_Control = $n_control";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();

        // Asigna los valores a variables para utilizar en el formulario
        $n_control= $fila['N_Control'];
        $apellido_paterno = $fila['Apellido_Paterno'];
        $apellido_materno = $fila['Apellido_Materno'];
        $nombre = $fila['Nombre'];
        $semestre = $fila['Semestre'];
        $carrera = $fila['Carrera'];
        $plan_estudio = $fila['Plan_Estudio'];
        $instituto = $fila['Instituto'];
        $traslado_carrera = $fila['Traslado_Carrera'];
        $con_plan = $fila['Con_Plan'];
        $debido = $fila['Debido'];
        
       

    } else {
        echo "No se encontraron datos para el N_Control proporcionado.";
    }
}



?>


<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos/Style2.css">
  
	
    <link href="../estilos/Formulario.css" rel="stylesheet">

    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Modificar Solicitud</title>
</head>

<body >

 

<header>
  <nav class="navbar navbar-expand-lg navbar-light" style="background-color: rgb(48, 16, 107);">
    <!-- Contenido de la barra de navegación -->
    <div class="container-fluid">
      <a class="navbar-brand" href="../conexiones/ConsultarSolicitudTraslado.php" style="color: white;">Atras</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-link" href="../conexiones/ModificarSolicitudTraslado.php"  style="color: white;">MODIFICAR SOLICITUD DE TRASLADO</a>
        </div>
      </div>
    </div>
  </nav>
  
      
     
      
      <div  id="main"></div>
    
    </header>
  
    
<main class="form-signin w-100 m-auto">

  
<form action="<?=$_SERVER['PHP_SELF']?>" id="Formulario_Solicitud_tRASLADO" class="campos_registro" accept-charset="utf-8" method="post">
  
  <h1 style="color: #0a0707; font-size: 20px;"></h1>
      <p></p>
      <div class="form-floating">
        <input class="form-control" type="number" name="N_Control" id="n_control"  placeholder  required min="0" value = "<?php echo $n_control; ?>">
        <label for="n_control">Numero de Control</label>
    </div><p></p>
      <div class="form-floating">
        <input  class="form-control" type="text" name="Apellido_Paterno" id="apellido_paterno" placeholder required value = "<?php echo $apellido_paterno; ?>">
        <label for="floatingInput">Apellido Paterno</label>
      </div><p></p>
      <div class="form-floating">
        <input  class="form-control" type="text" name="Apellido_Materno" id="apellido_materno" placeholder required value = "<?php echo $apellido_materno; ?>">
        <label for="floatingInput">Apellido Materno</label>
      </div><p></p>
    <div class="form-floating">
      <input  class="form-control" type="text" name="Nombre" id="nombre" placeholder required value = "<?php echo $nombre; ?>">
      <label for="floatingInput">Nombre</label>
    </div><p></p>
  
  <div class="form-floating">
    <input class="form-control" name="Semestre" id="semestre" placeholder required value = "<?php echo $semestre; ?>">
    <label for="semestre">Semestre</label>
</div>
<p></p>
<div class="form-floating">
    <input class="form-control" name="Carrera" id="carrera" placeholder required value = "<?php echo $carrera; ?>">
    <label for="carrera">Carrera</label>
</div><p></p>
<div class="form-floating">
    <input class="form-control" name="Plan_Estudio" id="plan_estudio" placeholder required value = "<?php echo $plan_estudio; ?>">
    <label for="pan de estudio">Pan de Estudio</label>
</div><p></p>
    <div class="form-floating">
      <input  class="form-control" type="text" name="Instituto" id="instituto" placeholder  required value = "<?php echo $instituto; ?>">
      <label for="floatingInput">Instituto al acual quiere su traslado -EJEMPLO INSTITUTO SUPERIOR DE ZAMORA</label>
    </div><p></p>
    <div class="form-floating">
        <input  class="form-control" type="text" name="Traslado_Carrera" id="traslado_carrera" placeholder  required value = "<?php echo $traslado_carrera; ?>">
        <label for="floatingInput">Traslado de la carrera de - POR EJEMPLO INGENIERIA CIVIL</label>
      </div><p></p>
      <div class="form-floating">
        <input  class="form-control" type="text" name="Con_Plan" id="con_plan" placeholder  required value = "<?php echo $con_plan; ?>">
        <label for="floatingInput">Con Plan de Estudio - POR EJEMPLO IELC-2010-211</label>
    
      </div><p></p>
      <div class="form-floating">
        <input  class="form-control" type="text" name="Debido" id="debido" placeholder  required value = "<?php echo $debido; ?>">
        <label for="floatingInput">Debido A - DESCRIBE EL MOTIVO</label>
      </div><p></p>
    <div class="d-flex justify-content-center align-items-center mt-3 position-relative"> <!-- Agrega las clases para centrar -->
      <button class="btn btn-primary py-2 ancho-personalizado position-absolute">Modificar Constancia</button>
  </div>
  
  
</form>


</main>

<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>


    </body>
</html>
