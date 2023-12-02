
<?php
require '../conexiones/Conexion.php';

// Recupera los datos existentes para llenar el formulario
if (isset($_GET['Nomina'])) {
  $folio = $_GET['Nomina']; // Asegúrate de pasar el ID correctamente, por ejemplo, desde la URL

  // Consulta para obtener los datos existentes
  $$sql = "SELECT * FROM docentes WHERE Nomina = '$nomina'";
  $resultado = $conn->query($sql);

  if ($resultado->num_rows > 0) {
      $fila = $resultado->fetch_assoc();

      // Asigna los valores a variables para utilizar en el formulario

      $apellido_paterno = $fila['Apellido_Paterno'];
      $apellido_materno = $fila['Apellido_Materno'];
      $nombre = $fila['Nombre'];
      $nomina = $fila['Nomina'];
      $cargo = $fila['Cargo'];
      $area = $fila['Area'];
      
  } else {
      echo "No se encontraron datos para la nomina proporcionado.";
  }
}
$apellido_paterno = '';
$apellido_materno = '';
$nombre = "";
$nomina ="";
$cargo ="";
$area = "";
// Cerrar la conexión a la base de datos
$conn->close();
?>




<!doctype html>
<html lang="en" data-bs-theme="auto">
  <head><script src="../assets/js/color-modes.js"></script>

    <meta charset="utf-8">
   
    <title>ANEXO XLII.COMISIONES</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sign-in/">

    <script src="../conexiones/ConexionComisiones.php"></script>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

<link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">



</head>

<body >

 

    <div class="Encabezado">
       
                <a>
           
        <img class="Logotipo" src="../img/logo.jpg">
           
                </a>
            <h1>ANEXO XLII.COMISIONES</h1>
     </div>
     <p></p><p></p><p></p>
    <link href="../estilos/Formulario.css" rel="stylesheet">
  
    
<main class="form-signin w-100 m-auto">

  
    <form action="../conexiones/ConexionComisiones.php" id="Formulario_Comisiones" class="campos_registro" accept-charset="utf-8" method="post">
  
      <h1 style="color: #0a0707; font-size: 20px;">
        Para asegurarnos de que procesemos su solicitud de manera oportuna y precisa, es importante que proporcione información completa y exacta en el formulario.
    </h1>
    <p></p>
    <div class="form-floating">
      <input class="form-control" type="text" name="Nomina" id="nomina" placeholder required  value="<?php echo $nomina; ?>">
      <label for="floatingInput">Nomina</label>
  </div> <p></p>
    <div class="form-floating">
      <input  class="form-control" type="text" name="Apellido_Paterno" id="apellido_paterno" placeholder required  value="<?php echo $apellido_paterno; ?>">
      <label for="floatingInput">Apellido Paterno</label>
    </div><p></p>
    <div class="form-floating">
      <input  class="form-control" type="text" name="Apellido_Materno" id="apellido_materno" placeholder required  value="<?php echo $apellido_materno; ?>">
      <label for="floati<p></p>ngInput">Apellido Materno</label>
    </div>
  <div class="form-floating">
    <input  class="form-control" type="text" name="Nombre" id="nombre" placeholder required value="<?php echo $nombre; ?>">
    <label for="floatingInput">Nombre</label>
  </div><p></p>
    <div class="form-floating">
      <input class="form-control" name="Cargo" id="cargo" placeholder required  value="<?php echo $cargo; ?>">

      <label for="cargo">Cargo</label>
  </div>
 
<p></p>

    
  <div class="form-floating">
    <input class="form-control" name="Area" id="area" placeholder required  value="<?php echo $area; ?>">
        
    <label for="area">Area</label>
</div><p></p>
<p><p></p></p>
    <div class="form-floating">
      <input class="form-control" type="text" name="Lugar" id="lugar"  placeholder  required>
      <label for="floatingInput">Lugar - ESPECIFICAR EL DOMICILIO</label>
  </div><p></p>
    <div class="form-floating">
      <input  class="form-control" type="text" name="Asunto" id="asunto" placeholder required>
      <label  for="floatingInput">Asunto</label>
    </div><p></p>
    <div class="form-floating">
      <select class="form-control" name="Transporte" id="transporte" placeholder required>
          <option value="Si">Si</option>
          <option value="No">No</option>
      </select>
      <label for="transporte">Requiere Transporte</label>
  </div><p></p>
  <div class="form-floating">
      <select class="form-control" name="Viaticos" id="viaticos" placeholder required>
          <option value="Si">Si</option>
          <option value="No">No</option>
      </select>
      <label for="viaticos">Viaticos</label>
  </div><p></p>
  <div class="form-floating">
      <select class="form-control" name="Especificacion_Viaticos" id="especificacion_viaticos" placeholder required>
          <option value="Ninguno">Ninguno</option>
          <option value="Otro">Otro</option>
      </select>
      <label for="Especificacion_Viaticos">Especifique los Viaticos</label>
  </div><p></p>

  <input type="text" class="form-control" name="OtroEspecificacion_Viaticos" id="otroespecificacion_viaticos" style="display:none;" placeholder="Escribe el Viatico">

<script>
    var especificacionViaticosSelect = document.getElementById("especificacion_viaticos");
    var otroespecificacionViaticosInput = document.getElementById("otroespecificacion_viaticos");

    especificacionViaticosSelect.addEventListener("change", function() {
        if (this.value === "Otro") {
            otroespecificacionViaticosInput.style.display = "block";
            otroespecificacionViaticosInput.setAttribute("required", "true");
        } else {
            otroespecificacionViaticosInput.style.display = "none";
            otroespecificacionViaticosInput.removeAttribute("required");
            otroespecificacionViaticosInput.value = "";
        }
    });
</script>
<div class="form-floating">
  <input  class="form-control" type="text" name="Observaciones" id="observaciones" placeholder required>
  <label for="floatingInput">Observaciones</label>
</div><p></p>

    <div class="form-floating">
      <input  class="form-control"  type="date" name="Dia_Salida" id="dia_salida" placeholder required>
      <label for="floatingInput">Dia de Salida</label>
    </div><p></p>
    <div class="form-floating">
      <input  class="form-control"  type="time" name="Hora_Salida" id="hora_salida" placeholder required>
      <label for="floatingInput">Hora de Salida</label>
    </div><p></p>
    <div class="form-floating">
      <input  class="form-control"  type="date" name="Dia_Regreso" id="dia_regreso" placeholder required>
      <label for="floatingInput">Dia de Regreso</label>
    </div><p></p>
    <div class="form-floating">
      <input  class="form-control"  type="time" name="Hora_Regreso" id="hora_regreso" placeholder required>
      <label for="floatingInput">Hora de Regreso</label>
    </div><p></p>
    <div class="d-flex justify-content-center align-items-center mt-3 position-relative"> <!-- Agrega las clases para centrar -->
      <button class="btn btn-primary py-2 ancho-personalizado position-absolute">Generar Constancia</button>
  </div>
  </form>


</main>

<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>


    </body>
</html>
