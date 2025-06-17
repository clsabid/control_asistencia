<?php 
ob_start();
session_start();

if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
} else {
  require "../config/global.php";
  date_default_timezone_set('America/Lima');
  require 'header.php';
?>
<!-- CONTENIDO -->
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <!-- Cabecera -->
          <div class="box-header with-border">
            <h1 class="box-title">Reporte de asistencia por fecha</h1>
          </div>

          <!-- Filtros -->
          <div class="panel-body" style="padding: 20px;">
            <div class="form-row">
              <div class="form-group col-lg-3 col-md-6 col-sm-12">
                <label for="fecha_inicio">Fecha Inicio</label>
                <input type="date" class="form-control" id="fecha_inicio" value="<?php echo date('Y-m-d'); ?>">
              </div>

              <div class="form-group col-lg-3 col-md-6 col-sm-12">
                <label for="fecha_fin">Fecha Fin</label>
                <input type="date" class="form-control" id="fecha_fin" value="<?php echo date('Y-m-d'); ?>">
              </div>

              <div class="form-group col-lg-4 col-md-6 col-sm-12">
                <label for="empleado_id">Estudiantes</label>
                <select name="empleado_id" id="empleado_id" class="form-control selectpicker" data-live-search="true" required>
                  <!-- Opciones cargadas con JS -->
                </select>
              </div>

              <div class="form-group col-lg-2 col-md-6 col-sm-12" style="margin-top: 25px;">
                <button class="btn btn-success btn-block" onclick="listar();"><i class="fa fa-search"></i> Mostrar</button>
              </div>
            </div>
          </div>

          <!-- Tabla -->
          <div class="panel-body table-responsive">
            <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Código</th>
                  <th>Estudiantes</th>
                  <th>Fecha</th>
                  <th>Hora</th>
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody>
                <!-- Cuerpo cargado dinámicamente -->
              </tbody>
              <tfoot>
                <tr>
                  <th>#</th>
                  <th>Código</th>
                  <th>Estudiantes</th>
                  <th>Fecha</th>
                  <th>Hora</th>
                  <th>Tipo</th>
                </tr>
              </tfoot>
            </table>
          </div>

        </div> <!-- .box -->
      </div> <!-- .col-md-12 -->
    </div> <!-- .row -->
  </section>
</div>
<!-- FIN CONTENIDO -->

<?php 
  require 'footer.php'; 
?>
<!-- Enlace al JS correcto -->
<script src="scripts/rptasistencia.js"></script>

<?php 
} // cierre del else
ob_end_flush(); 
?>
