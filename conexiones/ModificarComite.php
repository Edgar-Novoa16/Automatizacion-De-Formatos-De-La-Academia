<?php
require 'Conexion.php';
require '../vendor/autoload.php'; // Incluye la autoloader de Composer
use PhpOffice\PhpWord\TemplateProcessor;

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recupera los datos del formulario


    $id = $_POST['Id']; // Asegúrate de tener un campo de ID en tu formulario
    $nombre = mb_strtoupper ($_POST["Nombre"]);
    $apellido_paterno = mb_strtoupper ($_POST['Apellido_Paterno']);
    $apellido_materno = mb_strtoupper($_POST['Apellido_Materno']);
    $asunto = mb_strtoupper($_POST['Asunto']);
    $n_telefono = ($_POST["N_Telefono"]);
    $semestre =($_POST["Semestre"]);
    $correo_electronico = ($_POST["Correo_Electronico"]);
    $n_control = ($_POST["N_Control"]);
    $solicito = mb_strtoupper($_POST["Solicito"]);
    $motivo = mb_strtoupper($_POST['Motivo']);
    $razon = ($_POST["Razon"]);
 
    // ...
    // Actualiza los datos en la base de datos
    $sql = "UPDATE from_carta_comite SET 
            Apellido_Paterno = '$apellido_paterno',
            Apellido_Materno = '$apellido_materno',
            Nombre = '$nombre',
            Asunto = '$asunto',
            N_Telefono = '$n_telefono',
            Semestre = '$semestre',
            Correo_Electronico = '$correo_electronico',
            N_Control = '$n_control',
            Solicito = '$solicito',
            Motivo = '$motivo',
            Razon = '$razon'
            

            WHERE Id = $id";

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

// Eliminar el archivo temporal después de obtener su contenido
unlink($tempFilePath);

// Actualizar el campo "Comision" en la tabla "from_comite"
$sqlUpdate = "UPDATE from_carta_comite SET Constancia = ? WHERE Id = ?";
$stmt = $conn->prepare($sqlUpdate);
$stmt->bind_param("si", $fileContent, $id);
$stmt->execute();


        echo "<script>alert('¡Constancia Modificada con Exito!');
        window.location.href = '../conexiones/ConsultarComite.php';</script>";
    } else {
        echo "Error al actualizar los datos: " . $conn->error;
    }

    // Cierra la conexión
    $conn->close();
}



$id = '';
$apellido_paterno = '';
$apellido_materno = '';
$nombre = "";
$asunto ="";
$n_telefono ="";
$correo_electronico = "";
$n_control ="";
$semestre = "";
$solicito ="";
$razon ="";
$motivo ="";

// Recupera los datos existentes para llenar el formulario
if (isset($_GET['Id'])) {
    $id = $_GET['Id']; // Asegúrate de pasar el ID correctamente, por ejemplo, desde la URL
  
    // Consulta para obtener los datos existentes
    $sql = "SELECT * FROM from_carta_comite WHERE Id = $id";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();

        // Asigna los valores a variables para utilizar en el formulario
        $id= $fila['Id'];
        $apellido_paterno = $fila['Apellido_Paterno'];
        $apellido_materno = $fila['Apellido_Materno'];
        $nombre = $fila['Nombre'];
        $asunto = $fila['Asunto'];
        $n_telefono = $fila['N_Telefono'];
        $correo_electronico = $fila['Correo_Electronico'];
        $n_control = $fila['N_Control'];
        $semestre = $fila['Semestre'];
        $solicito = $fila['Solicito'];
        $razon = $fila['Razon'];
        $motivo = $fila['Motivo'];

    } else {
        echo "No se encontraron datos para el Id proporcionado.";
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
	<script src="../conexiones/ConsultarComite.php"></script>
  <script src="../conexiones/CargaComite.php"></script>


    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Modificar Comite</title>
</head>

<body >

 

<header>
  <nav class="navbar navbar-expand-lg navbar-light" style="background-color: rgb(48, 16, 107);">
    <!-- Contenido de la barra de navegación -->
    <div class="container-fluid">
      <a class="navbar-brand" href="../conexiones/ConsultarComite.php" style="color: white;">Atras</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-link" href="../conexiones/ModificarComite.php"  style="color: white;">MODIFICAR COMITE</a>
        </div>
      </div>
    </div>
  </nav>
  
      
     
      
      <div  id="main"></div>
    
    </header>
  
    
<main class="form-signin w-100 m-auto">

  
    <form action="<?=$_SERVER['PHP_SELF']?>" id="Formulario_Comite" class="campos_registro" accept-charset="utf-8" method="post">

    <h1 style="color: #0a0707; font-size: 20px;">
    </h1>
    
    <div class="form-floating">
      <input  class="form-control" type="text" name="Id" id="id" placeholder required  value = "<?php echo $id; ?>"  readonly>
      <label for="floatingInput">Id</label>
    </div><p></p>
    <div class="form-floating">
      <input  class="form-control" type="text" name="Apellido_Paterno" id="apellido_paterno" placeholder required  value="<?php echo $apellido_paterno;?>">
      <label for="floatingInput">Apellido Paterno</label>
    </div><p></p>
    <div class="form-floating">
      <input  class="form-control" type="text" name="Apellido_Materno" id="apellido_materno" placeholder required value="<?php echo $apellido_materno; ?>">
      <label for="floatingInput">Apellido Materno</label>
    </div><p></p>
  <div class="form-floating">
    <input  class="form-control" type="text" name="Nombre" id="nombre" placeholder required  value="<?php echo $nombre; ?>">
    <label for="floatingInput">Nombre</label>
  </div><p></p>
    <div class="form-floating">
      <input class="form-control" name="Asunto" id="asunto" placeholder required  value="<?php echo $asunto; ?>">
      <label for="asunto">Asunto</label>
      </div>
<p></p>
    <div class="form-floating">
      <input  class="form-control" type="number" name="N_Telefono" id="n_telefono" placeholder  required min="0"  value="<?php echo $n_telefono; ?>">
      <label for="floatingInput">Telefono</label>
    </div><p></p>
    <div class="form-floating">
      <input class="form-control" type="number" name="Semestre" id="semestre" placeholder="Semestre" required min="1" max="12" pattern="[1-9]|1[0-2]"  value="<?php echo $semestre; ?>">
      <label for="semestre">Semestre</label>
  </div>  <p></p>
    <div class="form-floating">
      <input class="form-control" type="email" name="Correo_Electronico" id="correo_electronico" placeholder="name@example.com" required  value="<?php echo $correo_electronico; ?>">
      <label for="floatingInput">Correo Electronico</label>
    </div><p></p>
    <div class="form-floating">
      <input class="form-control" type="number" name="N_Control" id="n_control"  placeholder  required min="0"  value="<?php echo $n_control; ?>">
      <label for="n_control">Numero de Control</label>
  </div><p></p>
  
    <div class="form-floating">
      <input  class="form-control" type="text" name="Solicito" id="solicito" placeholder required  value="<?php echo $solicito; ?>">
      <label  for="floatingInput">Solicito de la manera mas atenta</label>
    </div><p></p>
    <div class="form-floating">
      <input class="form-control" name="Motivo" id="motivo" placeholder required  value="<?php echo $motivo; ?>">

      <label for="motivo">Motivo</label>
  </div><p></p>
    <div class="form-floating">
      <input  class="form-control"  type="text" name="Razon" id="razon" placeholder required  value="<?php echo $razon; ?>">
      <label for="floatingInput">Explica bien cual es la razon</label>
    </div><p></p>
    <div class="d-flex justify-content-center align-items-center mt-3 position-relative"> <!-- Agrega las clases para centrar -->
      <button class="btn btn-primary py-2 ancho-personalizado position-absolute">Modificar</button>
  </div>
  </form>


</main>

<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>


    </body>
</html>
