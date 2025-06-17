<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Asistencia de Usuarios</title>

    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../public/css/_all-skins.min.css">
    <link rel="stylesheet" href="../public/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../public/datatables/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../public/datatables/responsive.dataTables.min.css">
    <link rel="stylesheet" href="../public/css/font-awesome.css">
    </head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php
        // include 'header.php';
        // include 'sidebar.php';
        ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Asistencia de Usuarios <small>Registro y Reportes</small></h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
                    <li class="active">Asistencia Usuarios</li>
                </ol>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Registrar Asistencia de Usuario</h3>
                            </div>
                            <div class="box-body">
                                <form id="form_asistencia_usuario" name="form_asistencia_usuario" method="POST">
                                    <div class="form-group">
                                        <label for="inputCodigoUsuarioAsistencia">Código de Usuario (QR):</label>
                                        <input type="text" class="form-control" id="inputCodigoUsuarioAsistencia" name="codigo" placeholder="Escanea o introduce el código" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Tipo de Asistencia:</label><br>
                                        <label class="radio-inline">
                                            <input type="radio" name="tipo_asistencia_usuario" value="entrada" checked> Entrada
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="tipo_asistencia_usuario" value="salida"> Salida
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Registrar Asistencia</button>
                                    </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Listado de Asistencias de Usuarios</h3>
                            </div>
                            <div class="box-body table-responsive">
                                <table id="tbllistadoAsistenciaUsuarios" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                        <th>ID</th>
                                        <th>Código</th>
                                        <th>Usuario</th>
                                        <th>Hora</th>
                                        <th>Tipo</th>
                                        <th>Fecha</th>
                                    </thead>
                                    <tbody>
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div><?php
        // include 'footer.php';
        ?>
    </div><script src="../public/js/jquery-3.1.1.min.js"></script>
    <script src="../public/js/bootstrap.min.js"></script>
    <script src="../public/js/app.min.js"></script>

    <script src="../public/datatables/jquery.dataTables.min.js"></script>
    <script src="../public/datatables/dataTables.buttons.min.js"></script>
    <script src="../public/datatables/buttons.html5.min.js"></script>
    <script src="../public/datatables/buttons.colVis.min.js"></script>
    <script src="../public/datatables/jszip.min.js"></script>
    <script src="../public/datatables/pdfmake.min.js"></script>
    <script src="../public/datatables/vfs_fonts.js"></script>
    <script src="../public/datatables/dataTables.responsive.min.js"></script>

    <script src="../public/js/bootbox.min.js"></script>

    <script type="text/javascript" src="scripts/asistenciausuario.js"></script> 
    
    </body>
</html>