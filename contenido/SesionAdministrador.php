
<?php
require '../conexiones/Conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['Usuario'];
    $contraseña = $_POST['Contraseña'];

    // Validación de entrada
    if (empty($usuario) || empty($contraseña)) {
        echo "<script>alert('Usuario o contraseña incorrecta.Por favor, ingresa el usuario y la contraseña.');</script>";
        exit();
    }

    // Consulta SQL
    $sql = "SELECT * FROM administradores WHERE Usuario = ? AND Contraseña = ?";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $contraseña);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Verificar si se encontraron resultados
    if ($result->num_rows > 0) {
        // Usuario encontrado y contraseña correcta, redirigir a la página deseada
        header("Location: ../contenido/Consultar.php?Usuario=" . $usuario . ""); exit();
    } else {
        // Usuario no encontrado o contraseña incorrecta, mostrar mensaje de error
        echo "<script>alert('Usuario o contraseña incorrecta.');</script>";
    }
}

// Cerrar la conexión
$conn->close();
?>
<!doctype html>
<html lang="en" data-bs-theme="auto">
  <head><script src="../assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>CONSULTAR CONSTANCIAS</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sign-in/">

    

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

<link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">


<link href="../estilos/Formulario.css" rel="stylesheet">

    <link href="../sign-in.css" rel="stylesheet">
  </head>

 
    
  <body >
    <header>
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: rgb(48, 16, 107);">
          <!-- Contenido de la barra de navegación -->
          <div class="container-fluid">
            <a class="navbar-brand" href="../Principal.html" style="color: white;">Atras</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
           
          </div>
        </nav>
    
            <div  id="main"></div>
          
          </header>
    
  <p><p></p></p>
        
        <span class="visually-hidden" id="bd-theme-text">ADMINISTRADOR</span>
   

    
<main class="form-signin w-100 m-auto">
  <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
    <img class="mb-4" src="../img/iconoAdministrador.png" alt="" width="72" height="57">
    <h1 class="h3 mb-3 fw-normal">Administrador</h1>

    <div class="form-floating">
      <input type="text" class="form-control" name="Usuario" id="usuario" required >
      <label for="floatingInput">Usuario</label>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control" name=" Contraseña" id=" contraseña"  required  placeholder="Password">
      <label for="floatingPassword">Contraseña</label>
    </div>

<button class="btn btn-primary w-100 py-2 custom-button" type="submit">Entrar</button>   
   <p class="mt-5 mb-3 text-body-secondary">&copy; Division de Ingenieria en Sistemas Computacionales</p>
  </form>
</main>
<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>




    </body>
</html>


