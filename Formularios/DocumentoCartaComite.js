document.getElementById('Formulario_Carta_Comite').addEventListener('submit', function (e) {
    e.preventDefault();

    const nombre = document.getElementById('nombre').value;
    const asunto = document.getElementById('asunto').value;
    const n_telefono = document.getElementById('n_telefono').value;
    const semestre = document.getElementById('semestre').value;
    const correo_electronico = document.getElementById('correo_electronico').value;
    const n_control = document.getElementById('n_control').value;
    const solicito = document.getElementById('solicito').value;
    const motivo = document.getElementById('motivo').value;
    const razon = document.getElementById('razon').value;

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'ANEXO XLII. SOLICITUD COMITE.docx', true);
    xhr.responseType = 'arraybuffer';


    xhr.onload = function () {
        const data = new Uint8Array(xhr.response);
        const buffer = new ArrayBuffer(data.length);
        const view = new Uint8Array(buffer);
        for (let i = 0; i < data.length; i++) {
            view[i] = data[i];
        }

        const zip = new JSZip();
        zip.loadAsync(buffer).then(function (doc) {
            const docxtemplater = new Docxtemplater();
            docxtemplater.loadZip(doc);

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

            docxtemplater.setData(context);

            try {
                docxtemplater.render();
            } catch (error) {
                console.error('Error al renderizar la plantilla:', error);
                return;
            }

            const blob = docxtemplater.getZip().generate({ type: 'blob' });

            saveAs(blob, 'documento_generado.docx');
        });
    };

    xhr.send();
});
