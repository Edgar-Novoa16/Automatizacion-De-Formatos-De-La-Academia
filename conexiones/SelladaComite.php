<?php
require 'Conexion.php';
require '../vendor/autoload.php'; // Incluye la autoloader de Composer
date_default_timezone_set('America/Mexico_City');

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Recupera los datos del formulario
  $id = $_POST['Id']; 
  $nombre =  mb_strtoupper($_POST["Nombre"]);
  $apellido_paterno =  mb_strtoupper($_POST['Apellido_Paterno']);
  $apellido_materno =  mb_strtoupper($_POST['Apellido_Materno']);
  $asunto =  mb_strtoupper($_POST["Asunto"]);
  $n_control =  mb_strtoupper($_POST["N_Control"]);
 
  // Verifica si se ha subido un archivo
  if (isset($_FILES["Constancia"]) && $_FILES["Constancia"]["error"] == UPLOAD_ERR_OK) {
      $constancia = $_FILES["Constancia"]["tmp_name"];
      $constanciaContent = file_get_contents($constancia);
      
      // Actualiza el archivo de comision en la base de datos
      $stmt = $conn->prepare("UPDATE from_carta_comite SET Constancia = ?,  Estatus =  'COMPLETA' WHERE Id = ?");
      $stmt->bind_param("si", $constanciaContent, $id);
      
      if ($stmt->execute()) {
          echo "<script>alert('¡Constancia subida con éxito!');
          window.location.href = '../conexiones/ConsultarComite.php';</script>";
      } else {
          echo "Error al subir comisión: " . $stmt->error;
      }
      
      $stmt->close();
  } else {
      echo "Error al subir el archivo de constancia.";
  }

  // Cierra la conexión
  $conn->close();
}

$id = '';
$apellido_paterno = '';
$apellido_materno = '';
$nombre = "";
$asunto ="";
$n_control="";


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
        $n_control = $fila['N_Control'];
     

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
	


    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Subir Comite Sellada </title>
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
          <a class="nav-link" href=""  style="color: white;">COMITE SELLADA</a>
        </div>
      </div>
    </div>
  </nav>
  
      
     
      
      <div  id="main"></div>
    
    </header>
  
    
<main class="form-signin w-100 m-auto">

  
<form action="<?=$_SERVER['PHP_SELF']?>" id="Formulario_Comite" class="campos_registro" accept-charset="utf-8" method="post" enctype="multipart/form-data">
    <h1 style="color: #0a0707; font-size: 20px;">Subir Comite Sellada</h1>
    <p></p>
    <div class="form-floating">
    <input  class="form-control" type="text" name="Id" id="id" placeholder required  value = "<?php echo $id; ?>"  readonly>
      <label for="floatingInput">Id</label>
    </div><p></p>
    <div class="form-floating">
      <input  class="form-control" type="text" name="Apellido_Paterno" id="apellido_paterno" placeholder required  value="<?php echo $apellido_paterno;?>" readonly>
      <label for="floatingInput">Apellido Paterno</label>
    </div><p></p>
    <div class="form-floating">
      <input  class="form-control" type="text" name="Apellido_Materno" id="apellido_materno" placeholder required value="<?php echo $apellido_materno; ?>" readonly>
      <label for="floatingInput">Apellido Materno</label>
    </div><p></p>
  <div class="form-floating">
    <input  class="form-control" type="text" name="Nombre" id="nombre" placeholder required  value="<?php echo $nombre; ?>" readonly>
    <label for="floatingInput">Nombre</label>
  </div><p></p>
    <div class="form-floating">
      <input class="form-control" name="Asunto" id="asunto" placeholder required  value="<?php echo $asunto; ?>" readonly>
      <label for="asunto">Asunto</label>
      </div>
<p></p>
    <div class="form-floating">
      <input class="form-control" type="number" name="N_Control" id="n_control"  placeholder  required min="0"  value="<?php echo $n_control; ?>" readonly>
      <label for="n_control">Numero de Control</label>
  </div><p></p>
  <div class="form-floating">
    <input class="form-control" type="file" name="Constancia" id="constancia" accept=".doc, .docx" placeholder required>
    <label for="constancia">Constancia</label>
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