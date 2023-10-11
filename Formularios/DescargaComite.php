<?php

require 'vendor/autoload.php'; // Carga el autoloader de Composer

use PhpOffice\PhpWord\TemplateProcessor;

function descargaComite($nombre, $asunto, $n_telefono, $semestre, $correo_electronico, $n_control, $solicito, $motivo, $razon) {
    // Ruta de la plantilla del documento .docx
    $plantilla = 'ANEXO XLII. SOLICITUD COMITE.docx';

    // Crear un nuevo procesador de plantillas con la plantilla existente
    $templateProcessor = new TemplateProcessor($plantilla);

    // Reemplazar las variables en la plantilla con los datos del formulario
    $templateProcessor->setValue('NOMBRE', $nombre);
    $templateProcessor->setValue('ASUNTO', $asunto);
    $templateProcessor->setValue('N_TELEFONO', $n_telefono);
    $templateProcessor->setValue('SEMESTRE', $semestre);
    $templateProcessor->setValue('CORREO_ELECTRONICO', $correo_electronico);
    $templateProcessor->setValue('N_CONTROL', $n_control);
    $templateProcessor->setValue('SOLICITO', $solicito);
    $templateProcessor->setValue('MOTIVO', $motivo);
    $templateProcessor->setValue('RAZON', $razon);

    // Guardar el documento temporalmente
    $tempFilePath = 'documento_generado.docx';
    $templateProcessor->saveAs($tempFilePath);

    // Establecer las cabeceras para la descarga
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="documento_generado.docx"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($tempFilePath));

    // Enviar el archivo
    readfile($tempFilePath);

    // Eliminar el archivo temporal después de enviarlo
    unlink($tempFilePath);
}

// Llamar a la función pasando los datos necesarios
descargaComite($nombre, $asunto, $n_telefono, $semestre, $correo_electronico, $n_control, $solicito, $motivo, $razon);
$conn->close();

?>
