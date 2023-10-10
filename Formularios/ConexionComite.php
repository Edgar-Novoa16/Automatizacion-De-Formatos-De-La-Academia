
<?php
// Incluir o requerir el archivo que contiene la función
require 'Conexion.php';
conexionbd();
// Llamar a la función para establecer la conexión
$conn = conexionbd();

$nombre = $_POST["Nombre"];
$asunto = $_POST["Asunto"];
$n_telefono = $_POST["N_Telefono"];
$semestre = $_POST["Semestre"];
$correoElectronico = $_POST["Correo_Electrónico"];
$n_control = $_POST["N_Control"];
$solicito = $_POST["Solicito"];
$motivo = $_POST["Motivo"];
$razon = $_POST["Razon"];

$mensaje = "";

// Consulta SQL para verificar si el número de control ya existe
$consulta_existencia = "SELECT * FROM from_carta_comite WHERE N_Control = '$n_control'";
$resultado = $conn->query($consulta_existencia);

if ($resultado->num_rows > 0) {
    $mensaje = "El número de control $n_control ya existe en la base de datos. Por favor, elige otro número de control.";
} else {
    $sql = "INSERT INTO from_carta_comite (N_Control, Nombre, Asunto, N_Telefono, Semestre, Correo_Electronico, Solicito, Motivo, Razon, Fecha) 
    VALUES ('$n_control', '$nombre', '$asunto', $n_telefono, $semestre, '$correoElectronico', '$solicito', '$motivo', '$razon', CURDATE())";

    if ($conn->query($sql) === TRUE) {
        $mensaje = "Datos insertados correctamente";
    } else {
        $mensaje = "Error al insertar datos: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<body>
    <script>
        // Función para mostrar el mensaje emergente
        function mostrarMensaje(mensaje) {
            alert(mensaje);
            // Redirige de nuevo al formulario después de mostrar el mensaje
            window.location.href = "FormularioCartaComite.html";
        }

        // Llama a la función para mostrar el mensaje
        mostrarMensaje("<?php echo $mensaje; ?>");
    </script>
</body>
</html>


<script>
document.getElementById('Formulario_Carta_Comite').addEventListener('submit', function (event) {
    event.preventDefault(); // Evita el envío del formulario

    const nombre = document.getElementById('nombre').value;
    const asunto = document.getElementById('asunto').value;
    const n_telefono = document.getElementById('n_telefono').value;
    const semestre = document.getElementById('semestre').value;
    const correo_electronico = document.getElementById('correo_electronico').value;
    const n_control = document.getElementById('n_control').value;
    const solicito = document.getElementById('solicito').value;
    const motivo = document.getElementById('motivo').value;
    const razon = document.getElementById('razon').value;

    // Ruta de tu plantilla DOCX
    const templateURL = 'Formularios\ANEXO XLII. SOLICITUD COMITE.docx';


    fetch(templateURL)
        .then((response) => response.arrayBuffer())
        .then((arrayBuffer) => {
            const zip = new JSZip(arrayBuffer);

            const doc = new Docxtemplater().loadZip(zip);

            const context = {
                NOMBRE: nombre,
                ASUNTO: asunto,
                N_TELEFONO: n_telefono,
                SEMESTRE: semestre,
                CORREO_ELECTRONICO: correo_electronico,
                N_CONTROL: n_control,
                SOLICITO: solicito,
                MOTIVO: motivo,
                RAZON: razon
            };

            doc.setData(context);

            try {
                doc.render();
            } catch (error) {
                console.error('Error al renderizar la plantilla:', error);
                return;
            }

            const generatedBlob = doc.getZip().generate({
                type: 'blob',
                mimeType: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            });

            const filename = 'documento_generado.docx';

            if (window.navigator.msSaveOrOpenBlob) {
                // Para IE
                window.navigator.msSaveOrOpenBlob(generatedBlob, filename);
            } else {
                const link = document.createElement('a');
                link.href = window.URL.createObjectURL(generatedBlob);
                link.download = filename;
                link.style.display = 'none';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        })
        .catch((error) => {
            console.error('Error al cargar la plantilla:', error);
        });
});
</script>
