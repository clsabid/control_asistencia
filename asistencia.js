var tabla;
var tabla_asistencia;
var stream; // Declarada globalmente para gestionar la cámara

function init() {
    listar(); // Inicia la carga de la tabla de asistencias

    // Evento para limpiar atributos de accesibilidad cuando un modal se cierra
    // Esto previene que VoiceOver o NVDA detecten elementos ocultos.
    $(document).on('hidden.bs.modal', '.modal', function () {
        $('body').removeAttr('aria-hidden');
        if (document.activeElement) {
            document.activeElement.blur(); // Quita el foco del elemento activo
        }
    });

    // Evento adicional para asegurar que los wrappers principales también se liberen
    $(document).on('hidden.bs.modal', function () {
        $('body > .wrapper, body > .content-wrapper').removeAttr('aria-hidden');
    });

    // Manejo del formulario de asistencia para captura por teclado/scanner
    $("#form_asistencia").on("submit", function(e){
        e.preventDefault(); // Evita el envío tradicional del formulario HTML
        var codigo = $("#inputCodigoAsistencia").val();
        var tipo = $('input[name="tipo_asistencia"]:checked').val(); // Obtiene el valor del radio button seleccionado

        if (codigo && tipo) {
            registrarAsistenciaPorCodigo(codigo, tipo);
        } else {
            bootbox.alert("Por favor, introduce el código y selecciona el tipo de asistencia (entrada/salida).");
        }
        $("#inputCodigoAsistencia").val(""); // Limpia el input después de intentar registrar
    });

    // Botón para registrar entrada (alternativa al formulario con radio buttons)
    $("#btnRegistrarEntrada").on("click", function(){
        var codigo = $("#inputCodigoAsistencia").val();
        if (codigo) {
            registrarAsistenciaPorCodigo(codigo, 'entrada');
        } else {
            bootbox.alert("Por favor, introduce el código para registrar la entrada.");
        }
        $("#inputCodigoAsistencia").val("");
    });

    // Botón para registrar salida (alternativa al formulario con radio buttons)
    $("#btnRegistrarSalida").on("click", function(){
        var codigo = $("#inputCodigoAsistencia").val();
        if (codigo) {
            registrarAsistenciaPorCodigo(codigo, 'salida');
        } else {
            bootbox.alert("Por favor, introduce el código para registrar la salida.");
        }
        $("#inputCodigoAsistencia").val("");
    });
}

// Función para registrar la asistencia del USUARIO por su código (via AJAX)
function registrarAsistenciaPorCodigo(codigo, tipo) {
    $.ajax({
        url: "../controlador/Asistencia.php?op=registrar", // Ruta al controlador PHP
        type: "POST", // Método POST para enviar datos
        data: { codigo: codigo, tipo: tipo }, // Datos a enviar al servidor
        dataType: "json", // Esperamos una respuesta JSON
        success: function(response) {
            // Muestra la alerta con Bootbox
            bootbox.alert({
                message: response.message, // Mensaje de éxito o error del servidor
                callback: function() {
                    // Acciones de limpieza después de cerrar la alerta
                    if (document.activeElement) {
                        document.activeElement.blur();
                    }
                    $('body').removeAttr('aria-hidden');
                    $('body > .wrapper, body > .content-wrapper').removeAttr('aria-hidden');
                }
            });
            // Si la tabla de DataTables está inicializada, la recarga para mostrar el nuevo registro
            if (typeof tabla !== 'undefined' && tabla !== null) {
                tabla.ajax.reload(null, false); // 'null, false' para no resetear la paginación
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Log de errores en la consola del navegador
            console.error("Error en la solicitud AJAX de registro de asistencia:", textStatus, errorThrown, jqXHR.responseText);
            // Muestra un mensaje de error genérico al usuario
            bootbox.alert({
                message: "Hubo un error al registrar la asistencia. Revise la consola para más detalles.",
                callback: function() {
                    if (document.activeElement) {
                        document.activeElement.blur();
                    }
                    $('body').removeAttr('aria-hidden');
                    $('body > .wrapper, body > .content-wrapper').removeAttr('aria-hidden');
                }
            });
        }
    });
}

// Función para inicializar y cargar la tabla de asistencias con DataTables
function listar() {
    tabla = $('#tbllistado').dataTable({
        "aProcessing": true, // Activar el indicador de procesamiento
        "aServerSide": true, // Activar el procesamiento del lado del servidor (PHP)
        dom: 'Bfrtip', // Definir los elementos de la interfaz de DataTables
        buttons: [
            'copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf' // Botones de exportación
        ],
        "ajax": {
            url: '../controlador/Asistencia.php?op=listar', // URL para obtener los datos de la tabla
            type: "get", // Método GET
            dataType: "json", // Esperamos JSON
            error: function(e) {
                console.log(e.responseText); // Log de errores si la carga de datos falla
            }
        },
        "bDestroy": true, // Permite que la tabla sea destruida y recreada
        "iDisplayLength": 10, // Número de registros a mostrar por página
        "order": [[0, "desc"]] // Ordenar la tabla por la primera columna (ID) de forma descendente
    }).DataTable(); // Inicializa DataTables
    tabla_asistencia = tabla; // Asigna la instancia de DataTables a tabla_asistencia (si necesitas una referencia adicional)
}

// ==================================================================================
// ✅ FUNCIONES PARA ACTIVAR Y DESACTIVAR LA CÁMARA (MÓVIL O COMPUTADORA)
// ==================================================================================

// Inicia la transmisión de la cámara y la muestra en el div con id "camara"
function iniciaCamara() {
    // Configuración para la cámara: preferir la cámara ambiental (trasera en móviles) y sin audio
    const constraints = { video: { facingMode: "environment" }, audio: false };
    const camaraDiv = document.getElementById('camara');

    // Asegurarse de que el div de la cámara existe
    if (!camaraDiv) {
        console.error("El elemento con id 'camara' no fue encontrado.");
        bootbox.alert("Error: El contenedor de la cámara no está disponible en la página.");
        return;
    }

    // Limpia cualquier contenido previo en el div de la cámara
    camaraDiv.innerHTML = '';

    // Crea un elemento <video> para mostrar la transmisión de la cámara
    const video = document.createElement('video');
    video.setAttribute('autoplay', ''); // Iniciar reproducción automáticamente
    video.setAttribute('playsinline', ''); // Necesario para que funcione en dispositivos móviles (iOS)
    video.style.width = '100%';
    video.style.maxHeight = '300px';
    camaraDiv.appendChild(video); // Añade el video al div

    // Solicita acceso a los medios del usuario (cámara)
    navigator.mediaDevices.getUserMedia(constraints)
        .then(function(mediaStream) {
            stream = mediaStream; // Asigna el stream globalmente para poder apagarlo después
            video.srcObject = stream; // Asigna el stream al elemento de video
        })
        .catch(function(err) {
            // Manejo de errores si no se puede acceder a la cámara
            console.error("No se pudo acceder a la cámara:", err);
            bootbox.alert("No se pudo acceder a la cámara: " + err.message + ". Asegúrate de haber dado permisos.");
        });
}

// Apaga la transmisión de la cámara y limpia el div
function apagaCamara() {
    if (stream) {
        // Detiene todas las pistas del stream (video, audio, etc.)
        stream.getTracks().forEach(track => track.stop());
        stream = null; // Libera la referencia al stream
    }
    const camaraDiv = document.getElementById('camara');
    if (camaraDiv) {
        camaraDiv.innerHTML = ''; // Limpia el contenido del div de la cámara
    }
}

// Inicializa el script cuando el DOM esté completamente cargado
$(document).ready(function() {
    init();
});