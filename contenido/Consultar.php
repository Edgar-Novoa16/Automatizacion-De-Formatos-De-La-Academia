


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos/Style2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link rel="stylesheet" href="../estilos/Formulario.css">
	<script src="../conexiones/ConsultarComite.php"></script>
  <script src="../conexiones/CargaComite.php"></script>


    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Menú</title>
</head>

<style>
  body {
    background-image: url("../img/fondo.png");
    height: 100vh;
    background-size: 1280px 640px; /* Ancho: 200px, Alto: 150px */
    min-height: 20rem;
    background-repeat: no-repeat; /* Evita la repetición de la imagen */
  }
</style>

<body >
 

<header>
  <nav class="navbar navbar-expand-lg navbar-light" style="background-color: rgb(48, 16, 107);">
    <!-- Contenido de la barra de navegación -->
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-link" href="../conexiones/ConsultarComite.php" onclick="cargarContenido('cartacomite')" style="color: white;">Carta Comite</a>
          <a class="nav-link" href="../conexiones/ConsultarLiberacionResidencia.php" onclick="cargarContenido('solicitudResidencia')" style="color: white;">Solicitud Liberacion Residencia</a>
          <a class="nav-link" href="../conexiones/ConsultarSolicitudTraslado.php" onclick="cargarContenido('solicitudTraslado')" style="color: white;">Solicitud Traslado</a>
          <a class="nav-link" href="../conexiones/ConsultarComisiones.php" onclick="cargarContenido('comisionesDocentes')" style="color: white;">Comisiones Docentes</a>
          
        </div>
      </div>
    </div>
  </nav>
  
      
     
      
      <div  id="contenido"></div>
    
    </header>
   
        <script>


    

function cargarContenido(opcion) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById("contenido").innerHTML = xhr.responseText;
        }
    };

    // Determinar qué archivo PHP cargar según la opción seleccionada
    var archivo;
    if (opcion === "cartacomite") {
        archivo = "ConsultaComite.php";
    } else if (opcion === "solicitudResidencia") {
        archivo = "ConsultarLiberacionResidencia.php";
    } else if (opcion === "solicitudTraslado") {
        archivo = "ConsultarSolicitud.Traslado.php";
    } else if (opcion === "comisionesDocentes") {
        archivo = "ConsultarComisiones.php";
    }

    // Realizar la solicitud AJAX para cargar el archivo PHP
    xhr.open("GET", archivo, true);
    xhr.send();
}

</script>
</body>

</html>


