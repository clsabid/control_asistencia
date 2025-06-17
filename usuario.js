var tabla;

//function que se ejecuta al inicio
function init(){
    mostrarform(false);

    listar();

    $("#formulario").on("submit", function(e){
        guardaryeditar(e);
    })

    // ✅ CORRECCIÓN CLAVE: Este manejador asegura que el foco se limpia
    // cuando cualquier modal de Bootstrap/Bootbox se oculta.
    // Es una capa de seguridad general para tu aplicación.
    $(document).on('hidden.bs.modal', '.modal', function () {
        if (document.activeElement && this.contains(document.activeElement)) {
            console.warn("Foco atrapado en modal oculto, intentando moverlo.");
            document.activeElement.blur();
        }
    });
}

function limpiar(){
    $("#idusuario").val("");
    $("#nombre").val("");
    $("#apellidos").val("");
    $("#email").val("");
    $("#telefono").val("");
    $("#imagenmuestra").attr("src", "");
    $("#imagenactual").val("");
    // $("#idusuario").val(""); // Comentado porque ya está arriba
}

function mostrarform(flag){
    limpiar();
    if (flag){
        $("#listadoregistros").hide();
        $("#formularioregistro").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnAgregar").hide();
    }
    else{
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
   tabla = $('#tbllistado').dataTable(
   {
       "aProcessing": true,//Activamos el procesamiento del datatables
       "aServerSide": true,//Paginacion y filtrado realizados por el servidor
       dom: 'Bfrtip',//Definimos los elementos del control de la tabla
       buttons: [
           'copyHtml5',
           'excelHtml5',
           'csvHtml5',
           'pdf'
       ],
       "ajax":
               {
                   url: '../controlador/Usuario.php?op=listar',
                   type : "get",
                   dataType : "json",
                   error: function(e){
                       console.log(e.responseText);
                   }
               },
       "bDestroy": true,
       "iDisplayLength": 10,//Paginacion
       "order": [[ 0, "desc" ]]//Ordenar (columna, orden)
   }).DataTable();
}


function guardaryeditar(e)
{
    e.preventDefault(); //No se activara la accion predeterminada
    $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../controlador/Usuario.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos)
        {
            // ✅ CORRECCIÓN CLAVE: Añadido callback a bootbox.alert para quitar el foco.
            bootbox.alert(datos, function() {
                // Se ejecuta cuando el usuario hace clic en el botón "OK" del alert
                if (document.activeElement) {
                    document.activeElement.blur(); // Quita el foco
                }
            });
            mostrarform(false);
            tabla.ajax.reload();
        }

    });
    limpiar();
}


function mostrar(idusuario)
{
    $.post("../controlador/Usuario.php?op=mostrar",{idusuario : idusuario}, function(data, status)
    {
        data = JSON.parse(data);
        mostrarform(true);

        $("#nombre").val(data.nombre);
        $("#apellidos").val(data.apellidos);
        $("#email").val(data.email);
        $("#login").val(data.login);
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src","../files/usuarios/"+data.imagen);
        $("#imagenactual").val(data.imagen);
        $("#idusuario").val(data.id);

    });
}

//Funcion para desactivar
function desactivar(idusuario){
    bootbox.confirm("¿Esta seguro de desactivar este dato?", function(result){
        if(result){
            $.post("../controlador/Usuario.php?op=desactivar", {idusuario : idusuario}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
        // ✅ CORRECCIÓN CLAVE: Añadido para quitar el foco después de la interacción con el confirm.
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });
}

function activar(idusuario){
    bootbox.confirm("¿Esta seguro de activar este dato?", function(result){
        if(result){
            $.post("../controlador/Usuario.php?op=activar", {idusuario : idusuario}, function(e){
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
        // ✅ CORRECCIÓN CLAVE: Añadido para quitar el foco después de la interacción con el confirm.
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });
}


init();