<?php
require "../config/conexion.php";

class Usuario
{
    // Constructor vacío
    public function __construct()
    {
    }

    // Insertar nuevo usuario
    public function insertar($nombre, $apellidos, $login, $email, $clavehash, $imagen)
    {
        $sql = "INSERT INTO usuarios (nombre, apellidos, login, email, password, imagen, estado) 
                VALUES ('$nombre', '$apellidos', '$login', '$email', '$clavehash', '$imagen', '1')";
        return ejecutarConsulta($sql);
    }

    // Editar usuario existente
    public function editar($idusuario, $nombre, $apellidos, $login, $email, $clavehash, $imagen)
    {
        if (!empty($clavehash)) {
            $sql = "UPDATE usuarios 
                    SET nombre='$nombre', apellidos='$apellidos', login='$login', email='$email', password='$clavehash', imagen='$imagen' 
                    WHERE id='$idusuario'";
        } else {
            $sql = "UPDATE usuarios 
                    SET nombre='$nombre', apellidos='$apellidos', login='$login', email='$email', imagen='$imagen' 
                    WHERE id='$idusuario'";
        }
        return ejecutarConsulta($sql);
    }

    // Desactivar usuario
    public function desactivar($idusuario)
    {
        $sql = "UPDATE usuarios SET estado='0' WHERE id='$idusuario'";
        return ejecutarConsulta($sql);
    }

    // Activar usuario
    public function activar($idusuario)
    {
        $sql = "UPDATE usuarios SET estado='1' WHERE id='$idusuario'";
        return ejecutarConsulta($sql);
    }

    // Mostrar usuario por ID
    public function mostrar($idusuario)
    {
        $sql = "SELECT * FROM usuarios WHERE id='$idusuario'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Listar todos los usuarios
    public function listar()
    {
        $sql = "SELECT * FROM usuarios";
        return ejecutarConsulta($sql);
    }

    // Contar usuarios
    public function cantidad_usuario()
    {
        $sql = "SELECT COUNT(*) AS nombre FROM usuarios";
        return ejecutarConsulta($sql);
    }

    // Verificar login (login y clave hash deben coincidir y estado = 1)
    public function verificar($login, $clave)
    {
        $sql = "SELECT id AS idusuario, nombre, imagen, login 
                FROM usuarios 
                WHERE login = '$login' AND password = '$clave' AND estado = '1'";
        return ejecutarConsulta($sql);
    }
}
