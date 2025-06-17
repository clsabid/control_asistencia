var tabla;

function init(){
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e){
        guardaryeditar(e);
    });

    // Manejador para ocultar el foco cuando un modal de Bootstrap se oculta
    $(document).on('hidden.bs.modal', '.modal', function () {
        // Asegúrate de que el body no tenga aria-hidden si el modal se cierra
        $('body').removeAttr('aria-hidden');
        // Si hay un elemento activo que fue parte del modal, sácalo de foco
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });
    
    // Si estás usando un tema de AdminLTE o similar que envuelve el body/content
    // también es buena idea revisar y quitar aria-hidden de los contenedores principales
    $(document).on('hidden.bs.modal', function () {
        $('body > .wrapper, body > .content-wrapper').removeAttr('aria-hidden');
    });
}

function limpiar(){
    $("#empleado_id").val("");
    $("#nombre").val("");
    $("#apellidos").val("");
    $("#documento_numero").val("");
    $("#telefono").val("");
    $("#codigo").val("");
}

function mostrarform(flag){
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistro").show();
        $("#btnGuardar").prop("disabled", false); // Asegura que esté habilitado al mostrar el formulario
        $("#btnAgregar").hide();
    } else {
        $("#listadoregistros").show();
        $("#formularioregistro").hide();
        $("#btnAgregar").show();
    }
}

function cancelarform(){
    limpiar();
    mostrarform(false);
}

function listar(){
    tabla = $('#tbllistado').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf'],
        "ajax": {
            url: '../controlador/Empleado.php?op=listar',
            type: "get",
            dataType: "json",
            error: function(e){
                console.log("Error en la solicitud AJAX de listado:", e.responseText);
                // Si la conexión falla, se debería ver el error de PHP, pero si el JSON es inválido
                // bootbox.alert("Hubo un error al cargar la lista de empleados.");
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[ 0, "desc" ]]
    }).DataTable();
}

function guardaryeditar(e){
    e.preventDefault();
    console.log("¡Función guardaryeditar iniciada!"); 
    $("#btnGuardar").prop("disabled", true); 

    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../controlador/Empleado.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(response){
            // Mejorar el manejo de la alerta para la accesibilidad
            bootbox.alert({
                message: response.message || "Operación completada.",
                callback: function() {
                    // Asegurarse de que el foco se elimine después de que el usuario cierre la alerta
                    if (document.activeElement) {
                        document.activeElement.blur();
                    }
                    // Quitar aria-hidden del body o contenedores principales si Bootbox los añade
                    $('body').removeAttr('aria-hidden');
                    $('body > .wrapper, body > .content-wrapper').removeAttr('aria-hidden');
                }
            });
            mostrarform(false);
            tabla.ajax.reload();
            $("#btnGuardar").prop("disabled", false); 
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud AJAX de guardar/editar:", textStatus, errorThrown, jqXHR.responseText);
            bootbox.alert({
                message: "Hubo un error al intentar guardar/editar el empleado. Revise la consola para más detalles.",
                callback: function() {
                    if (document.activeElement) {
                        document.activeElement.blur();
                    }
                    $('body').removeAttr('aria-hidden');
                    $('body > .wrapper, body > .content-wrapper').removeAttr('aria-hidden');
                }
            });
            $("#btnGuardar").prop("disabled", false); 
        }
    });

    limpiar();
}

function mostrar(empleado_id){
    $.post({
        url: "../controlador/Empleado.php?op=mostrar",
        data: {empleado_id: empleado_id},
        dataType: "json",
        success: function(data){
            console.log("Datos recibidos para mostrar (ya parseados):", data); 

            if (data && typeof data === 'object' && Object.keys(data).length > 0) {
                mostrarform(true);

                $("#nombre").val(data.nombre || ''); 
                $("#apellidos").val(data.apellidos || '');
                $("#documento_numero").val(data.documento_numero || '');
                $("#telefono").val(data.telefono || '');
                $("#codigo").val(data.codigo || '');
                $("#empleado_id").val(data.id || ''); 

            } else {
                console.warn("No se encontraron datos para el empleado con ID:", empleado_id, "Respuesta del servidor:", data);
                bootbox.alert({
                    message: "No se pudo cargar la información del empleado. El registro no existe o el servidor no devolvió datos.",
                    callback: function() {
                        if (document.activeElement) {
                            document.activeElement.blur();
                        }
                        $('body').removeAttr('aria-hidden');
                        $('body > .wrapper, body > .content-wrapper').removeAttr('aria-hidden');
                    }
                });
                mostrarform(false);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud POST para mostrar empleado:", textStatus, errorThrown, jqXHR.responseText);
            bootbox.alert({
                message: "Error de conexión al cargar datos del empleado. Intente de nuevo. Revise la consola para más detalles.",
                callback: function() {
                    if (document.activeElement) {
                        document.activeElement.blur();
                    }
                    $('body').removeAttr('aria-hidden');
                    $('body > .wrapper, body > .content-wrapper').removeAttr('aria-hidden');
                }
            });
            mostrarform(false);
        }
    });
}

function eliminar(empleado_id){
    bootbox.confirm({
        message: "¿Está seguro de eliminar el registro?",
        callback: function(result){
            if(result){
                $.post({
                    url: "../controlador/Empleado.php?op=eliminar",
                    data: {empleado_id: empleado_id},
                    dataType: "json",
                    success: function(response){
                        bootbox.alert({
                            message: response.message || "Operación de eliminación completada.",
                            callback: function() {
                                if (document.activeElement) {
                                    document.activeElement.blur();
                                }
                                $('body').removeAttr('aria-hidden');
                                $('body > .wrapper, body > .content-wrapper').removeAttr('aria-hidden');
                            }
                        });
                        tabla.ajax.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Error en la solicitud POST para eliminar empleado:", textStatus, errorThrown, jqXHR.responseText);
                        bootbox.alert({
                            message: "Error de conexión al intentar eliminar el empleado. Revise la consola para más detalles.",
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
            // Asegurarse de que el foco se elimine después de que el usuario cierre el confirm
            // y quitar aria-hidden si aún está presente
            setTimeout(function() {
                if (document.activeElement) {
                    document.activeElement.blur();
                }
                $('body').removeAttr('aria-hidden');
                $('body > .wrapper, body > .content-wrapper').removeAttr('aria-hidden');
            }, 100);
        }
    });
}

init();