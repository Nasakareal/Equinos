<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compartir reporte</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h3>Preparando contenido para compartir...</h3>
    <p>Si no se abre automáticamente, usa el botón manual.</p>

    <button id="shareBtn" style="padding: 10px 16px; font-size: 16px; cursor: pointer;">
        Compartir ahora
    </button>

    <script>
        const texto = @json($texto);
        const imagenes = @json($imagenes);

        async function urlToFile(url, index) {
            const response = await fetch(url);
            const blob = await response.blob();
            const extension = blob.type.includes('png') ? 'png' :
                              blob.type.includes('webp') ? 'webp' : 'jpg';

            return new File([blob], `reporte_${index + 1}.${extension}`, {
                type: blob.type
            });
        }

        async function compartir() {
            try {
                let files = [];

                for (let i = 0; i < imagenes.length; i++) {
                    try {
                        const file = await urlToFile(imagenes[i], i);
                        files.push(file);
                    } catch (e) {
                        console.error('No se pudo convertir la imagen:', imagenes[i], e);
                    }
                }

                if (navigator.canShare && files.length > 0 && navigator.canShare({ files })) {
                    await navigator.share({
                        text: texto,
                        files: files
                    });
                    return;
                }

                if (navigator.share) {
                    await navigator.share({
                        text: texto
                    });
                    return;
                }

                await navigator.clipboard.writeText(texto);
                alert('Tu navegador no permite compartir archivos. Se copió el texto al portapapeles.');
            } catch (error) {
                console.error(error);
            }
        }

        document.getElementById('shareBtn').addEventListener('click', compartir);

        window.addEventListener('load', () => {
            compartir();
        });
    </script>
</body>
</html>
