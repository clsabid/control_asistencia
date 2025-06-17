<?php
// Activamos almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION['nombre'])) {
    header("Location: login.html");
} else {

    require 'header.php';
    require_once "../modelos/Usuario.php";
    $usuario = new Usuario();
    $rspta = $usuario->cantidad_usuario();
    $reg = $rspta->fetch_object();
    $total_usuarios = $reg->nombre; // Asumo que aquí quieres acceder a $reg->nombre

    ?>
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="panel-body">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="small-box bg-orange">
                                    <div class="inner">
                                        <h5 style="font-size: 20px;">
                                            <strong>Asistencia</strong>
                                        </h5>
                                        <p>Módulo</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                                    </div>
                                    <a href="asistencia.php" class="small-box-footer">
                                        Ir al módulo <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-xs-12">
                                <div class="small-box bg-orange">
                                    <div class="inner">
                                        <h4 style="font-size: 20px;">
                                            <strong>Empleados:</strong>
                                        </h4>
                                        <h3>Total <?php echo $total_usuarios; ?></h3>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-users" aria-hidden="true"></i>
                                    </div>
                                    <a href="empleado.php" class="small-box-footer">
                                        Agregar <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-xs-12">
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h5 style="font-size: 20px;">
                                            <strong>Reporte de asistencias</strong>
                                        </h5>
                                        <p>Módulo</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-list" aria-hidden="true"></i>
                                    </div>
                                    <a href="rptasistencia.php" class="small-box-footer">
                                        Ver reporte <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php
    require 'footer.php';
}
ob_end_flush();
?>
