<?php
require 'Conexion.php';
require '../vendor/autoload.php'; // Incluye la autoloader de Composer
use PhpOffice\PhpWord\TemplateProcessor;
date_default_timezone_set('America/Mexico_City');
// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recupera los datos del formulario


    $folio = $_POST['Folio']; // Asegúrate de tener un campo de ID en tu formulario
    $nombre =  mb_strtoupper($_POST["Nombre"]);
    $apellido_paterno =  mb_strtoupper($_POST['Apellido_Paterno']);
    $apellido_materno =  mb_strtoupper($_POST['Apellido_Materno']);
    $nomina =  mb_strtoupper($_POST["Nomina"]);
    $area  =  mb_strtoupper ($_POST["Area"]);
    $cargo =  mb_strtoupper ($_POST["Cargo"]);
    $lugar =  ($_POST["Lugar"]);
    $asunto =  ($_POST["Asunto"]);
    $transporte =  mb_strtoupper ($_POST["Transporte"]);
    $viaticos =  mb_strtoupper ($_POST["Viaticos"]);
    $especificacion_viaticos = mb_strtoupper ($_POST["Especificacion_Viaticos"]);
    $observaciones =  ($_POST["Observaciones"]);
    $dia_salida =  ($_POST["Dia_Salida"]);
    $hora_salida = ($_POST["Hora_Salida"]);
    $dia_regreso = ($_POST["Dia_Regreso"]);
    $hora_regreso = ($_POST["Hora_Regreso"]);
    
    // ...
    // Actualiza los datos en la base de datos
    $sql = "UPDATE from_comisiones SET 
            Apellido_Paterno = '$apellido_paterno',
            Apellido_Materno = '$apellido_materno',
            Nombre = '$nombre',
            Nomina = '$nomina',
            Cargo = '$cargo',
            Area = '$area',
            Lugar = '$lugar',
            Asunto = '$asunto',
            Transporte = '$transporte',
            Viaticos = '$viaticos',
            Especificacion_Viaticos = '$especificacion_viaticos',
            Observaciones = '$observaciones',
            Dia_Salida = '$dia_salida',
            Hora_Salida = '$hora_salida',
            Dia_Regreso = '$dia_regreso',
            Hora_Regreso = '$hora_regreso'
            

            WHERE Folio = $folio";

    if ($conn->query($sql) === TRUE) {
      $plantilla = '../plantillas/FormatoComision.docx';
    
        // Crear un nuevo procesador de plantillas con la plantilla existente
        $templateProcessor = new TemplateProcessor($plantilla);
       // Establecer la configuración regional a español (o el idioma que prefieras)
       setlocale(LC_TIME, 'es_ES.utf8', 'es_ES', 'esp');
        // Obtener la fecha actual en el formato deseado 
        $fechaActual = strftime ('%e de %B de %Y');

         
        
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

/// Eliminar el archivo temporal después de obtener su contenido
unlink($tempFilePath);

// Actualizar el campo "Comision" en la tabla "from_comisiones"
$sqlUpdate = "UPDATE from_comisiones SET Comision = ? WHERE Folio = ?";
$stmt = $conn->prepare($sqlUpdate);
$stmt->bind_param("si", $fileContent, $folio);
$stmt->execute();


        echo "<script>alert('¡Comision Modificada con Exito!');
        window.location.href = '../conexiones/ConsultarComisiones.php';</script>";
    } else {
        echo "Error al actualizar los datos: " . $conn->error;
    }

    // Cierra la conexión
    $conn->close();
}
$folio = '';
$apellido_paterno = '';
$apellido_materno = '';
$nombre = "";
$nomina ="";
$cargo ="";
$area = "";
$lugar ="";
$asunto = "";
$transporte ="";
$viaticos ="";
$especificacion_viaticos ="";
$observaciones ="";
$dia_salida ="";
$hora_salida= "";
$dia_regreso="";
$hora_regreso="";


// Recupera los datos existentes para llenar el formulario
if (isset($_GET['Folio'])) {
    $folio = $_GET['Folio']; // Asegúrate de pasar el ID correctamente, por ejemplo, desde la URL
  
    // Consulta para obtener los datos existentes
    $sql = "SELECT * FROM from_comisiones WHERE Folio = $folio";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();

        // Asigna los valores a variables para utilizar en el formulario
        $folio= $fila['Folio'];
        $apellido_paterno = $fila['Apellido_Paterno'];
        $apellido_materno = $fila['Apellido_Materno'];
        $nombre = $fila['Nombre'];
        $nomina = $fila['Nomina'];
        $cargo = $fila['Cargo'];
        $area = $fila['Area'];
        $lugar = $fila['Lugar'];
        $asunto = $fila['Asunto'];
        $transporte = $fila['Transporte'];
        $viaticos = $fila['Viaticos'];
        $especificacion_viaticos = $fila['Especificacion_Viaticos'];
        $observaciones = $fila['Observaciones'];
        $dia_salida = $fila['Dia_Salida'];
        $hora_salida = $fila['Hora_Salida'];
        $dia_regreso = $fila['Dia_Regreso'];
        $hora_regreso = $fila['Hora_Regreso'];

    } else {
        echo "No se encontraron datos para el Folio proporcionado.";
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
	<script src="../conexiones/ConsultarComisiones.php"></script>
  <script src="../conexiones/CargaComisiones.php"></script>

  <link href="../estilos/Formulario.css" rel="stylesheet">

    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Modificar Comision</title>
</head>

<body >

 

<header>
  <nav class="navbar navbar-expand-lg navbar-light" style="background-color: rgb(48, 16, 107);">
    <!-- Contenido de la barra de navegación -->
    <div class="container-fluid">
      <a class="navbar-brand" href="../conexiones/ConsultarComisiones.php" style="color: white;">Atras</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-link" href="../conexiones/ModificarComision.php"  style="color: white;">MODIFICAR COMISION</a>
        </div>
      </div>
    </div>
  </nav>
  
      
     
      
      <div  id="main"></div>
    
    </header>
  
    
<main class="form-signin w-100 m-auto">

  
    <form action="<?=$_SERVER['PHP_SELF']?>" id="Formulario_Comisiones" class="campos_registro" accept-charset="utf-8" method="post">
  
      <h1 style="color: #0a0707; font-size: 20px;">
        Para asegurarnos de que procesemos su solicitud de manera oportuna y precisa, es importante que proporcione información completa y exacta en el formulario.
    </h1>
    <p></p>
    <div class="form-floating">
      <input  class="form-control" type="text" name="Folio" id="folio" placeholder required  value="<?php echo $folio; ?>" readonly>
      <label for="floatingInput">Folio</label>
    </div><p></p>
    <div class="form-floating">
      <input  class="form-control" type="text" name="Apellido_Paterno" id="apellido_paterno" placeholder required  value="<?php echo $apellido_paterno; ?>" readonly>
      <label for="floatingInput">Apellido Paterno</label>
    </div><p></p>
    <div class="form-floating">
      <input  class="form-control" type="text" name="Apellido_Materno" id="apellido_materno" placeholder required  value="<?php echo $apellido_materno; ?>" readonly>
      <label for="floati<p></p>ngInput">Apellido Materno</label>
    </div>
  <div class="form-floating">
    <input  class="form-control" type="text" name="Nombre" id="nombre" placeholder required  value="<?php echo $nombre; ?>" readonly>
    <label for="floatingInput">Nombre</label>
  </div><p></p>
    <div class="form-floating">
      <input class="form-control" name="Cargo" id="cargo" placeholder required  value="<?php echo $cargo; ?>" readonly>
      <label for="cargo">Cargo</label>
  </div>
  
    <div class="form-floating">
      <input class="form-control" type="text" name="Nomina" id="nomina" placeholder required  value="<?php echo $nomina; ?>" readonly>
      <label for="floatingInput">Nomina</label>
  </div> <p></p>
  <div class="form-floating">
    <input class="form-control" name="Area" id="area" placeholder required  value="<?php echo $area; ?>" readonly>
    <label for="area">Area</label>
</div><p></p>
<input type="text" class="form-control" name="OtroArea" id="otroarea" style="display:none;" placeholder="Escribe el Area" value="<?php echo $otroarea; ?>">

<p><p></p></p>
    <div class="form-floating">
      <input class="form-control" type="text" name="Lugar" id="lugar"  placeholder  required  value="<?php echo $lugar; ?>">
      <label for="floatingInput">Lugar - ESPECIFICAR EL DOMICILIO</label>
  </div><p></p>
    <div class="form-floating">
      <input  class="form-control" type="text" name="Asunto" id="asunto" placeholder required  value="<?php echo $asunto; ?>">
      <label  for="floatingInput">Asunto</label>
    </div><p></p>
    <div class="form-floating">
      <input class="form-control" name="Transporte" id="transporte" placeholder required  value="<?php echo $transporte; ?>">
      <label for="transporte">Requiere Transporte</label>
  </div><p></p>
  <div class="form-floating">
      <input class="form-control" name="Viaticos" id="viaticos" placeholder required  value="<?php echo $viaticos; ?>">
      <label for="viaticos">Viaticos</label>
  </div><p></p>
  <div class="form-floating">
      <input class="form-control" name="Especificacion_Viaticos" id="especificacion_viaticos" placeholder required  value="<?php echo $especificacion_viaticos; ?>">
      <label for="Especificacion_Viaticos">Especifique los Viaticos</label>
  </div><p></p>
<div class="form-floating">
  <input  class="form-control" type="text" name="Observaciones" id="observaciones" placeholder required  value="<?php echo $observaciones; ?>">
  <label for="floatingInput">Observaciones</label>
</div><p></p>

    <div class="form-floating">
      <input  class="form-control"  type="date" name="Dia_Salida" id="dia_salida" placeholder required  value="<?php echo $dia_regreso; ?>">
      <label for="floatingInput">Dia de Salida</label>
    </div><p></p>
    <div class="form-floating">
      <input  class="form-control"  type="time" name="Hora_Salida" id="hora_salida" placeholder required  value="<?php echo $hora_regreso; ?>">
      <label for="floatingInput">Hora de Salida</label>
    </div><p></p>
    <div class="form-floating">
      <input  class="form-control"  type="date" name="Dia_Regreso" id="dia_regreso" placeholder required  value="<?php echo $dia_regreso; ?>">
      <label for="floatingInput">Dia de Regreso</label>
    </div><p></p>
    <div class="form-floating">
      <input  class="form-control"  type="time" name="Hora_Regreso" id="hora_regreso" placeholder required  value="<?php echo $hora_regreso; ?>">
      <label for="floatingInput">Hora de Regreso</label>
    </div><p></p>
    <div class="d-flex justify-content-center align-items-center mt-3 position-relative"> <!-- Agrega las clases para centrar -->
      <button class="btn btn-primary py-2 ancho-personalizado position-absolute">Modificar</button>
  </div>
  </form>


</main>

<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>


    </body>
</html>
