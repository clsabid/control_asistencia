<?php 
ob_start();
session_start();
if(!isset($_SESSION['nombre'])){
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
                        <h1 class="box-title">Lista de usuarios <button class="btn btn-success" onclick="mostrarform(true)" id="btnAgregar"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                            
                        </div>
                    </div>
                    <div class="panel-body table-responsive" id="listadoregistros">

                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Login</th>
                                <th>Email</th>
                                <th>Imagen</th>
                                <th>Estado</th>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Login</th>
                                <th>Email</th>
                                <th>Imagen</th>
                                <th>Estado</th>
                            </tfoot> 
                        </table>

                    </div>
                    <div class='panel-body' id='formularioregistro'>
                        <form action="" name="formulario" id='formulario' method="POST">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for='nombre'>Nombre ( * ):</label>
                                <input class="form-control" type="hidden" name="idusuario" id="idusuario">
                                <input class="form-control" type="text" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for='apellidos'>Apellidos ( * ):</label>
                                <input class="form-control" type="text" name="apellidos" id="apellidos" maxlength="100" placeholder="Apellidos" required>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="login">Login ( * ):</label> <input class="form-control" type="text" name="login" id="login" maxlength="20" placeholder="nombre de usuario" required>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for='clave'>Clave de Ingreso ( * ):</label>
                                <input class="form-control" type="password" name="clave" id="clave" maxlength="64" placeholder="clave">
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="imagen">Imagen:</label>
                                <input class="form-control filestyle" data-buttonText="Seleccionar foto" type="file" name="imagen" id="imagen">
                                <input type="hidden" name="imagenactual" id="imagenactual">
                                <img src="" alt='Vista previa de imagen' width="150px" height="150px" id="imagenmuestra"> </div>
                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                                <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                            </div>
                        </form>

                    </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php 
require 'footer.php';
?>
<script src="scripts/usuario.js"></script> 
<?php 
} // Cierre del if(!isset($_SESSION['nombre']))
ob_end_flush();
?>