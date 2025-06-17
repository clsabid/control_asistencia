$("#frmAcceso").on('submit', function(e) {
    e.preventDefault();

    let logina = $("#logina").val().trim();
    let clavea = $("#clavea").val().trim();

    if (logina === "" || clavea === "") {
        bootbox.alert("Asegúrate de llenar todos los campos");
        return;
    }

    $.post("../controlador/usuario.php?op=verificar", { logina, clavea }, function(response) {
        try {
            let data = JSON.parse(response);

            if (data && data.idusuario) {
                window.location.href = "escritorio.php";
            } else {
                bootbox.alert("Usuario y/o contraseña incorrectos");
            }
        } catch (error) {
            bootbox.alert("Error al procesar la respuesta del servidor.");
            console.error("Respuesta no válida:", response);
        }
    }).fail(function() {
        bootbox.alert("Ocurrió un error en el servidor. Intenta más tarde.");
    });
});
