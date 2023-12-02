<?php
require 'Conexion.php';
require '../vendor/autoload.php'; // Incluye la autoloader de Composer
use PhpOffice\PhpWord\TemplateProcessor;

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recupera los datos del formulario


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
    
    
    // ...
    // Actualiza los datos en la base de datos
    $sql = "UPDATE from_liberacion_residencia SET 
            Nombre = '$nombre',
            Apellido_Paterno = '$apellido_paterno',
            Apellido_Materno = '$apellido_materno',
            Periodo = '$periodo',
            Nombre_Proyecto = '$nombre_proyecto',
            Nombre_Empresa = '$nombre_empresa',
            Asesor_Interno = '$asesor_interno',
            Primer_Docente = '$primer_docente',
            Segundo_Docente = '$segundo_docente',
            Calificacion = '$calificacion',
            Observaciones = '$observaciones'
            

            WHERE N_Control = $n_control";

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

/// Eliminar el archivo temporal después de obtener su contenido
unlink($tempFilePath);

// Actualizar el campo "Comision" en la tabla "from_comisiones"
$sqlUpdate = "UPDATE from_liberacion_residencia SET Constancia = ? WHERE N_Control = ?";
$stmt = $conn->prepare($sqlUpdate);
$stmt->bind_param("si", $fileContent, $n_control);
$stmt->execute();


        echo "<script>alert('¡Constancia Modificada con Exito!');
        window.location.href = '../conexiones/ConsultarLiberacionResidencia.php';</script>";
    } else {
        echo "Error al actualizar los datos: " . $conn->error;
    }

   
}
$n_control = '';
$apellido_paterno = '';
$apellido_materno = '';
$nombre = "";
$periodo ="";
$nombre_proyecto ="";
$nombre_empresa = "";
$asesor_interno ="";
$primer_docente = "";
$segundo_docente ="";
$calificacion ="";
$observaciones ="";




// Recupera los datos existentes para llenar el formulario
if (isset($_GET['N_Control'])) {
    $n_control = $_GET['N_Control']; // Asegúrate de pasar el ID correctamente, por ejemplo, desde la URL
  
    // Consulta para obtener los datos existentes
    $sql = "SELECT * FROM from_liberacion_residencia WHERE N_Control = $n_control";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();

        // Asigna los valores a variables para utilizar en el formulario
        $n_control= $fila['N_Control'];
        $apellido_paterno = $fila['Apellido_Paterno'];
        $apellido_materno = $fila['Apellido_Materno'];
        $nombre = $fila['Nombre'];
        $periodo = $fila['Periodo'];
        $nombre_proyecto = $fila['Nombre_Proyecto'];
        $nombre_empresa = $fila['Nombre_Empresa'];
        $asesor_interno = $fila['Asesor_Interno'];
        $primer_docente = $fila['Primer_Docente'];
        $segundo_docente = $fila['Segundo_Docente'];
        $calificacion = $fila['Calificacion'];
        $observaciones = $fila['Observaciones'];
       

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link rel="stylesheet" href="../estilos/Formulario.css">
	

    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Modificar Solicitud</title>
</head>

<body >

 

<header>
  <nav class="navbar navbar-expand-lg navbar-light" style="background-color: rgb(48, 16, 107);">
    <!-- Contenido de la barra de navegación -->
    <div class="container-fluid">
      <a class="navbar-brand" href="../conexiones/ConsultarLiberacionResidencia.php" style="color: white;">Atras</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-link" href="../conexiones/ModificarSolicitudResidencia.php"  style="color: white;">MODIFICAR LIBERACION DE RESIDENCIA</a>
        </div>
      </div>
    </div>
  </nav>
  
      
     
      
      <div  id="main"></div>
    
    </header>
  
    
<main class="form-signin w-100 m-auto">

  
<form action="<?=$_SERVER['PHP_SELF']?>" id="Formulario_Solicitud_Liberacion_Residencia" class="campos_registro" accept-charset="utf-8" method="post">
  
  <h1 style="color: #0a0707; font-size: 20px;"></h1>
      <p></p>
      <div class="form-floating">
      <input class="form-control" type="text" name="Apellido_Paterno" id="apellido_paterno" placeholder required value="<?php echo $apellido_paterno; ?>">
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
      <input class="form-control" type="number" name="N_Control" id="n_control"  placeholder  required min="0" value = "<?php echo $n_control; ?>">
      <label for="n_control">Numero de Control</label>
  </div><p></p>
  <div class="form-floating">
    <input  class="form-control" type="text" name="Periodo" id="periodo" placeholder required value = "<?php echo $periodo; ?>">
    <label for="floatingInput">Periodo en el que cursaste la residencia - Ejemplo: Enero - Junio 2023
    </label>
  </div><p></p>
  <div class="form-floating">
    <input class="form-control" type="text" name="Nombre_Proyecto" id="nombre_proyecto" placeholder required value = "<?php echo $nombre_proyecto; ?>">
    <label for="floatingInput">Nombre del Proyecto</label>
  </div><p></p>
  <div class="form-floating">
    <input  class="form-control" type="text" name="Nombre_Empresa" id="nombre_empresa" placeholder required value = "<?php echo $nombre_empresa; ?>">
    <label for="floatingInput">Nombre de la Empresa</label>
  </div><p></p>
  <div class="form-floating">
    <input  class="form-control"  type="text" name="Asesor_Interno" id="asesor_interno" placeholder required value = "<?php echo $asesor_interno; ?>">
    <label for="floatingInput">Nombre completo del Docente que fue tu asesor interno</label>
  </div><p></p>
  <div class="form-floating">
    <input  class="form-control"  type="text" name="Primer_Docente" id="primer_docente" placeholder required value = "<?php echo $primer_docente; ?>">
    <label for="floatingInput">Nombre del primer docente que participo como tu sinodal en la presentación de su residencia</label>
  </div><p></p>
  <div class="form-floating">
      <input  class="form-control"  type="text" name="Segundo_Docente" id="segundo_docente" placeholder required value = "<?php echo $segundo_docente; ?>">
      <label for="floatingInput">Nombre del segundo docente que participo como tu sinodal en la presentación de su residencia</label>
    </div><p></p>
    <div class="form-floating">
      <input  class="form-control"  type="number" name="Calificacion" id="calificacion" placeholder required value = "<?php echo $calificacion; ?>">
      <label for="floatingInput">Calificación que obtuviste</label>
    </div><p></p>
    <div class="form-floating">
      <input  class="form-control"   type="text" name="Observaciones" id="observaciones" placeholder required value = "<?php echo $observaciones; ?>">
      <label for="floatingInput">Observación adicional</label>
    </div><p></p>
   
    <div class="d-flex justify-content-center align-items-center mt-3 position-relative"> <!-- Agrega las clases para centrar -->
      <button class="btn btn-primary py-2 ancho-personalizado position-absolute">Modificar Constancia</button>
  </div>
  
  
</form>


</main>

<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>


    </body>
</html>
