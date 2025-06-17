<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Asistencia</title>

    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../public/datatables/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../public/datatables/responsive.dataTables.min.css">
    <style>
        /* Estilos para las etiquetas de tipo de asistencia */
        .label {
            display: inline-block;
            padding: .25em .4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
        }
        .bg-green { background-color: #00a65a !important; color: white; } /* Color para entrada */
        .bg-orange { background-color: #f39c12 !important; color: white; } /* Color para salida */
        .modal-backdrop { z-index: 1040 !important; } /* Ajuste z-index si tienes problemas con modales */
        .bootbox.modal { z-index: 1050 !important; } /* Ajuste z-index para bootbox si tienes problemas */
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h1 class="box-title">Asistencia de Empleados <button class="btn btn-success" id="btnagregar" onclick="iniciaCamara()">Activar Cámara</button> <button class="btn btn-danger" onclick="apagaCamara()">Desactivar Cámara</button></h1>
                                <div class="box-tools pull-right"></div>
                            </div>
                            <div class="panel-body">
                                <form name="form_asistencia" id="form_asistencia" method="POST">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Código:</label>
                                        <input type="text" class="form-control" name="inputCodigoAsistencia" id="inputCodigoAsistencia" placeholder="Escanea el código" required autofocus>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Tipo de Asistencia:</label><br>
                                        <label class="radio-inline"><input type="radio" name="tipo_asistencia" value="entrada" checked> Entrada</label>
                                        <label class="radio-inline"><input type="radio" name="tipo_asistencia" value="salida"> Salida</label>
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Registrar</button>
                                        </div>
                                </form>
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Vista previa de la cámara:</label>
                                    <div id="camara" style="width: 100%; height: 200px; background-color: #eee; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 5px;">
                                        <p style="color: #666;">Cámara inactiva</p>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body table-responsive" id="listadoregistros">
                                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                        <th>ID</th>
                                        <th>Código</th>
                                        <th>Empleado</th>
                                        <th>Hora</th>
                                        <th>Tipo</th>
                                        <th>Fecha</th>
                                    </thead>
                                    <tbody>
                                        </tbody>
                                    <tfoot>
                                        <th>ID</th>
                                        <th>Código</th>
                                        <th>Empleado</th>
                                        <th>Hora</th>
                                        <th>Tipo</th>
                                        <th>Fecha</th>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="../public/js/jquery-3.1.1.min.js"></script>
    <script src="../public/js/bootstrap.min.js"></script>
    <script src="../public/js/bootbox.min.js"></script> <script src="../public/datatables/jquery.dataTables.min.js"></script>
    <script src="../public/datatables/dataTables.buttons.min.js"></script>
    <script src="../public/datatables/buttons.html5.min.js"></script>
    <script src="../public/datatables/buttons.colVis.min.js"></script>
    <script src="../public/datatables/jszip.min.js"></script>
    <script src="../public/datatables/pdfmake.min.js"></script>
    <script src="../public/datatables/vfs_fonts.js"></script>
    <script src="../public/datatables/dataTables.responsive.min.js"></script>
    <script src="../public/datatables/responsive.bootstrap.min.js"></script>
    
    <script type="text/javascript" src="scripts/asistencia.js"></script> 

    </body>
</html>