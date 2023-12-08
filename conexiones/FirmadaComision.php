<?php
require 'Conexion.php';
require '../vendor/autoload.php'; // Incluye la autoloader de Composer
date_default_timezone_set('America/Mexico_City');

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Recupera los datos del formulario
  $folio = $_POST['Folio']; 
  $nombre =  mb_strtoupper($_POST["Nombre"]);
  $apellido_paterno =  mb_strtoupper($_POST['Apellido_Paterno']);
  $apellido_materno =  mb_strtoupper($_POST['Apellido_Materno']);
  $nomina =  mb_strtoupper($_POST["Nomina"]);
 
  // Verifica si se ha subido un archivo
  if (isset($_FILES["Comision"]) && $_FILES["Comision"]["error"] == UPLOAD_ERR_OK) {
      $comision = $_FILES["Comision"]["tmp_name"];
      $comisionContent = file_get_contents($comision);
      
      // Actualiza el archivo de comision en la base de datos
      $stmt = $conn->prepare("UPDATE from_comisiones SET Comision = ?,  Estatus =  'FIRMADA' WHERE Folio = ?");
      $stmt->bind_param("si", $comisionContent, $folio);
      
      if ($stmt->execute()) {
          echo "<script>alert('¡Comisión subida con éxito!');
          window.location.href = '../conexiones/ConsultarComisiones.php';</script>";
      } else {
          echo "Error al subir comisión: " . $stmt->error;
      }
      
      $stmt->close();
  } else {
      echo "Error al subir el archivo de comisión.";
  }

  // Cierra la conexión
  $conn->close();
}

$folio = '';
$apellido_paterno = '';
$apellido_materno = '';
$nombre = "";
$nomina ="";


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
	<script src="../conexiones/ConsultarComite.php"></script>
  <script src="../conexiones/CargaComite.php"></script>


    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Subir Firma Comision</title>
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
          <a class="nav-link" href=""  style="color: white;">COMISION FIRMADA</a>
        </div>
      </div>
    </div>
  </nav>
  
      
     
      
      <div  id="main"></div>
    
    </header>
  
    
<main class="form-signin w-100 m-auto">

  
<form action="<?=$_SERVER['PHP_SELF']?>" id="Formulario_Comisiones" class="campos_registro" accept-charset="utf-8" method="post" enctype="multipart/form-data">
    <h1 style="color: #0a0707; font-size: 20px;">Subir Comision Firmada</h1>
    <p></p>
    <div class="form-floating">
        <input class="form-control" type="text" name="Folio" id="folio" placeholder required value="<?php echo $folio; ?>" readonly>
        <label for="folio">Folio</label>
    </div>
    <p></p>
    <div class="form-floating">
        <input class="form-control" type="text" name="Apellido_Paterno" id="apellido_paterno" placeholder required value="<?php echo $apellido_paterno; ?>" readonly>
        <label for="apellido_paterno">Apellido Paterno</label>
    </div>
    <p></p>
    <div class="form-floating">
        <input class="form-control" type="text" name="Apellido_Materno" id="apellido_materno" placeholder required value="<?php echo $apellido_materno; ?>" readonly>
        <label for="apellido_materno">Apellido Materno</label>
    </div>
    <div class="form-floating">
        <input class="form-control" type="text" name="Nombre" id="nombre" placeholder required value="<?php echo $nombre; ?>" readonly>
        <label for="nombre">Nombre</label>
    </div>
    <p></p>
    <div class="form-floating">
        <input class="form-control" type="text" name="Nomina" id="nomina" placeholder required value="<?php echo $nomina; ?>" readonly>
        <label for="nomina">Nomina</label>
    </div>
    <p></p>
    <div class="form-floating">
        <input class="form-control" type="file" name="Comision" id="comision" accept=".pdf, .doc, .docx" placeholder required >
        <label for="comision">Comision Firmada</label>
    </div>
    <p></p>
    <div class="d-flex justify-content-center align-items-center mt-3 position-relative">
        <button class="btn btn-primary py-2 ancho-personalizado position-absolute">Enviar</button>
    </div>
</form>
</main>

<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>


    </body>
</html>
