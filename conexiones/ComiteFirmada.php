

<!doctype html>
<html lang="en" data-bs-theme="auto">
  <head><script src="../assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    
    <title>SOLICITUD FIRMADA ANEXO XLII. </title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sign-in/">

    <script src="../conexiones/ConexionComite.php"></script>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

<link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body >

 

    <div class="Encabezado">
       
                <a>
           
        <img class="Logotipo" src="../img/logo.jpg">
           
                </a>
            <h1>SOLICITUD FIRMADA ANEXO XLII.</h1>
     </div>
    
    <link href="../estilos/Formulario.css" rel="stylesheet">
  
    <p></p><p></p>
<main class="form-signin w-100 m-auto">

  
    <form action="" id="ComiteFirmada" class="campos_registro" accept-charset="utf-8" method="post">
  
      <h1 style="color: #0a0707; font-size: 20px;">
       De Favor Subir Solicitud Comite Academico.
    </h1>
    
    <div class="form-floating">
        <input class="form-control" type="number" name="N_Control" id="n_control"  placeholder  required min="0" value="<?php echo $n_control; ?>">
        <label for="n_control">Numero de Control</label>
    </div><p></p>
    <div class="form-floating">
      <input  class="form-control" type="text" name="Apellido_Paterno" id="apellido_paterno" placeholder required value="<?php echo $apellido_paterno; ?>">
      <label for="floatingInput">Apellido Paterno</label>
    </div><p></p>
    <div class="form-floating">
      <input  class="form-control" type="text" name="Apellido_Materno" id="apellido_materno" placeholder required value="<?php echo $apellido_materno; ?>">
      <label for="floatingInput">Apellido Materno</label>
    </div><p></p>
  <div class="form-floating">
    <input  class="form-control" type="text" name="Nombre" id="nombre" placeholder required value="<?php echo $nombre; ?>">
    <label for="floatingInput">Nombre</label>
  </div><p></p>
  <div class="form-floating">
   <select class="form-control" name="Asunto" id="asunto" placeholder required>
            <option value="Un semestre más para terminar mi carrera" <?php echo ($asunto == 'Un semestre más para terminar mi carrera') ? 'selected' : ''; ?>>Un semestre más para terminar mi carrera</option>
            <option value="Recursar materias en especial" <?php echo ($asunto == 'Recursar materias en especial') ? 'selected' : ''; ?>>Recursar materias en especial</option>
            <option value="Otro" <?php echo ($asunto == 'Otro') ? 'selected' : ''; ?>>Otro</option>
        </select>
      <label for="asunto">Asunto</label>
      </div>
  <div class="form-floating">
    <input  class="form-control"  type="file" name="Constancia" id="Constancia" accept=".pdf, .doc, .docx"  placeholder required>
    <label for="floatingInput">Subir Constancia</label>
  </div><p></p>
    </div><p></p>
    <div class="d-flex justify-content-center align-items-center mt-3 position-relative"> <!-- Agrega las clases para centrar -->
      <button class="btn btn-primary py-2 ancho-personalizado position-absolute">Subir Constancia</button>
  </div>
  </form>


</main>

<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>


    </body>
</html>


