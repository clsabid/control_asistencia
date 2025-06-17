var tabla_asistencia_usuario; // Variable específica para la tabla de asistencia de usuarios

function init() {
    listarAsistenciaUsuarios(); // Para listar la asistencia de usuarios existente al cargar la página

    // Manejador para ocultar el foco cuando un modal de Bootstrap se oculta
    $(document).on('hidden.bs.modal', '.modal', function () {
        $('body').removeAttr('aria-hidden');
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });

    // Si estás usando un tema de AdminLTE o similar que envuelve el body/content
    $(document).on('hidden.bs.modal', function () {
        $('body > .wrapper, body > .content-wrapper').removeAttr('aria-hidden');
    });

    // =========================================================================
    // ✅ CONFIGURACIÓN DEL FORMULARIO/BOTONES DE ASISTENCIA DE USUARIOS
    // =========================================================================
    // Asumiendo que tu HTML para la vista de asistencia de usuarios tiene un formulario
    // con un input para el código (ID "inputCodigoUsuarioAsistencia")
    // y botones para 'entrada' y 'salida' (IDs "btnRegistrarEntradaUsuario", "btnRegistrarSalidaUsuario")

    if ($("#form_asistencia_usuario").length) { // Verifica si el formulario existe
        $("#form_asistencia_usuario").on("submit", function(e){
            e.preventDefault();
            var codigo = $("#inputCodigoUsuarioAsistencia").val(); 
            var tipo = $('input[name="tipo_asistencia_usuario"]:checked').val(); // Si usas radio buttons

            if (codigo && tipo) {
                registrarAsistenciaUsuarioPorCodigo(codigo, tipo);
            } else {
                bootbox.alert("Por favor, introduce el código de usuario y selecciona el tipo de asistencia.");
            }
            $("#inputCodigoUsuarioAsistencia").val(""); 
        });
    }

    if ($("#btnRegistrarEntradaUsuario").length) {
        $("#btnRegistrarEntradaUsuario").on("click", function(){
            var codigo = $("#inputCodigoUsuarioAsistencia").val();
            if (codigo) {
                registrarAsistenciaUsuarioPorCodigo(codigo, 'entrada');
            } else {
                bootbox.alert("Por favor, introduce el código de usuario para registrar la entrada.");
            }
            $("#inputCodigoUsuarioAsistencia").val("");
        });
    }

    if ($("#btnRegistrarSalidaUsuario").length) {
        $("#btnRegistrarSalidaUsuario").on("click", function(){
            var codigo = $("#inputCodigoUsuarioAsistencia").val();
            if (codigo) {
                registrarAsistenciaUsuarioPorCodigo(codigo, 'salida');
            } else {
                bootbox.alert("Por favor, introduce el código de usuario para registrar la salida.");
            }
            $("#inputCodigoUsuarioAsistencia").val("");
        });
    }
    // =========================================================================
}

// Función para registrar la asistencia del USUARIO por su código
function registrarAsistenciaUsuarioPorCodigo(codigo, tipo) { 
    $.ajax({
        url: "../controlador/AsistenciaUsuario.php?op=registrar", // Apunta al NUEVO controlador
        type: "POST",
        data: { codigo: codigo, tipo: tipo }, 
        dataType: "json",
        success: function(response) {
            bootbox.alert({
                message: response.message,
                callback: function() {
                    if (document.activeElement) {
                        document.activeElement.blur();
                    }
                    $('body').removeAttr('aria-hidden');
                    $('body > .wrapper, body > .content-wrapper').removeAttr('aria-hidden');
                }
            });
            // Recargar tabla de asistencia de usuarios
            if (typeof tabla_asistencia_usuario !== 'undefined') { 
                tabla_asistencia_usuario.ajax.reload(null, false); 
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud AJAX de registro de asistencia de usuario:", textStatus, errorThrown, jqXHR.responseText);
            bootbox.alert({
                message: "Hubo un error al registrar la asistencia del usuario. Revise la consola para más detalles.",
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

function listarAsistenciaUsuarios() {
    tabla_asistencia_usuario = $('#tbllistadoAsistenciaUsuarios').dataTable({ // ✅ Nuevo ID para la tabla
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf'
        ],
        "ajax": {
            url: '../controlador/AsistenciaUsuario.php?op=listar', // Apunta al NUEVO controlador
            type: "get",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    }).DataTable();
}

init();