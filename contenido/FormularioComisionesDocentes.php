




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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
<main class="form-signin w-100 m-auto">

<h1 style="color: #0a0707; font-size: 20px;">
        Para asegurarnos de que procesemos su solicitud de manera oportuna y precisa, es importante que proporcione información completa y exacta en el formulario.
    
      </h1>
    <form action="../conexiones/ConexionComisiones.php" id="Formulario_Comisiones" class="campos_registro" accept-charset="utf-8" method="post">
    <h1 style="color: #0a0707; font-size: 20px;">
        Ingresar su nomina y espera a que cargue la informacion, y llenar las restante
    
      </h1>
     
    <p></p>
<div class="form-floating">
  <input class="form-control" type="text" name="Nomina" id="nomina" placeholder required onblur="buscar_datos();">
  <label for="floatingInput">Nomina</label>
</div>
<p></p>
<div class="form-floating">
  <input class="form-control" type="text" name="Apellido_Paterno" id="apellido_paterno" placeholder required >
  <label for="floatingInput">Apellido Paterno</label>
</div>
<p></p>
<div class="form-floating">
  <input class="form-control" type="text" name="Apellido_Materno" id="apellido_materno" placeholder required >
  <label for="floatingInput">Apellido Materno</label>
</div>
<div class="form-floating">
  <input class="form-control" type="text" name="Nombre" id="nombre" placeholder required >
  <label for="floatingInput">Nombre</label>
</div>
<p></p>
<div class="form-floating">
  <input class="form-control" name="Cargo" id="cargo" placeholder required >
  <label for="cargo">Cargo</label>
</div>
<p></p>
    
  <div class="form-floating">
    <input class="form-control" name="Area" id="area" placeholder required  >
        
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


    <script>
  function buscar_datos() {
    var nomina = $("#nomina").val();
    
    var parametros = {
      "buscar": "1",
      "Nomina": nomina,
    };
    
    $.ajax({
      url: "../conexiones/obtener_informacion_docente.php",
      type: "post",
      dataType: "json",
      data: parametros,
      beforeSend: function() {
        alert("Enviando...");
      },
      error: function() {
        alert("Error");
      },
      complete: function() {
        alert("¡Listo!");
      },
      success: function(valores) {
        $("#apellido_paterno").val(valores.apellido_paterno);
        $("#apellido_materno").val(valores.apellido_materno);
        $("#nombre").val(valores.nombre);
        $("#cargo").val(valores.cargo);
        $("#area").val(valores.area);
      }
    });
  }
  
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
</html>



