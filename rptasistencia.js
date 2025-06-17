let tabla;

function init() {
  listar();
  cargarEmpleados();
}

function cargarEmpleados() {
  $.post("../controlador/Empleado.php?op=select_empleado", function(r){
    $("#empleado_id").html(r);
    $('#empleado_id').selectpicker('refresh');
  });
}

function listar() {
  const fecha_inicio = $("#fecha_inicio").val();
  const fecha_fin = $("#fecha_fin").val();
  const empleado_id = $("#empleado_id").val();

  tabla = $('#tbllistado').DataTable({
    "aProcessing": true,
    "aServerSide": true,
    dom: 'Bfrtip',
    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf'],
    "ajax": {
      url: '../controlador/Asistencia.php?op=listar_asistencia',
      data: { fecha_inicio, fecha_fin, empleado_id },
      type: "get",
      dataType: "json",
      error: function(e) {
        console.log(e.responseText);
      }
    },
    "bDestroy": true,
    "iDisplayLength": 10,
    "order": [[0, "desc"]]
  });
}

init();
