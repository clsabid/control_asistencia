<?php
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: login.html");
} else {
    require 'header.php';
?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">

                    <div class="box-header with-border">
                        <h1 class="box-title">Lista de empleados 
                          <button class="btn btn-success" onclick="mostrarform(true)" id="btnAgregar">
                            <i class="fa fa-plus-circle"></i> Agregar
                          </button>
                        </h1>
                        <div class="box-tools pull-right"></div>
                    </div>

                    <!-- Tabla de empleados -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Documento</th>
                                    <th>Teléfono</th>
                                    <th>Código</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th>Opciones</th>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Documento</th>
                                    <th>Teléfono</th>
                                    <th>Código</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Formulario de registro -->
                    <div class="panel-body" id="formularioregistro" style="display:none;">
                        <form action="" name="formulario" id="formulario" method="POST">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="nombre">Nombre ( * ):</label>
                                <input class="form-control" type="hidden" name="empleado_id" id="empleado_id">
                                <input class="form-control" type="text" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="apellidos">Apellidos ( * ):</label>
                                <input class="form-control" type="text" name="apellidos" id="apellidos" maxlength="100" placeholder="Apellidos" required>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="documento_numero">Documento ( * ):</label>
                                <input class="form-control" type="text" name="documento_numero" id="documento_numero" maxlength="20" placeholder="Documento" required>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="telefono">Teléfono:</label>
                                <input class="form-control" type="text" name="telefono" id="telefono" maxlength="20" placeholder="Teléfono" required>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="codigo">Código de asistencia ( * ):</label>
                                <input class="form-control" type="text" name="codigo" id="codigo" maxlength="64" placeholder="Código" required>
                            </div>

                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                <button class="btn btn-primary" type="submit" id="btnGuardar">
                                  <i class="fa fa-save"></i> Guardar
                                </button>
                                <button class="btn btn-danger" onclick="cancelarform()" type="button">
                                  <i class="fa fa-arrow-circle-left"></i> Cancelar
                                </button>
                            </div>
                        </form>
                    </div>

                </div> <!-- .box -->
            </div> <!-- .col -->
        </div> <!-- .row -->
    </section>
</div>

<?php
    require 'footer.php';
?>
<!-- ✅ Script cargado correctamente -->
<script src="scripts/empleado.js"></script>
<?php }
ob_end_flush();
?>
