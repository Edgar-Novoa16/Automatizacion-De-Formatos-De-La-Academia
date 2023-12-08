<?php
	require 'Conexion.php';

	 
	 	if(isset($_POST['buscar']))
    { 
    	$nomina = $_POST['Nomina'];
    	$valores = array();
    	$valores['existe'] = "0";

    	//CONSULTAR
		  $resultados = mysqli_query($conn,"SELECT * FROM docentes WHERE Nomina = '$nomina'");
		  while($consulta = mysqli_fetch_array($resultados))
		  {
		  	$valores['existe'] = "1"; //Esta variable no la usamos en el vídeo (se me olvido, lo siento xD). Aqui la uso en la linea 97 de registro.php
		  	$valores['apellido_paterno'] = $consulta['Apellido_Paterno'];
		  	$valores['apellido_materno'] = $consulta['Apellido_Materno'];
            $valores['nombre'] = $consulta['Nombre'];
		  	$valores['cargo'] = $consulta['Cargo'];
		  	$valores['area'] = $consulta['Area'];				    
		  }
		  sleep(1);
		  $valores = json_encode($valores);
			echo $valores;
    }

   
?>

