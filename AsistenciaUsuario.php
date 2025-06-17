<?php
require_once __DIR__ . '/../config/conexion.php';

class AsistenciaUsuario
{
    public function __construct() {}

    // ✅ Función actualizada para registrar automáticamente entrada o salida
    public function registrarAsistencia($id_usuario, $codigo)
    {
        $fecha = date("Y-m-d");
        $hora = date("H:i:s");

        // Verificar los registros del día
        $sql_check = "SELECT tipo FROM asistencia_usuarios 
                      WHERE id_usuario = '$id_usuario' AND fecha = '$fecha' 
                      ORDER BY hora ASC";
        $result = ejecutarConsulta($sql_check);

        $tipos = [];
        while ($row = $result->fetch_assoc()) {
            $tipos[] = $row['tipo'];
        }

        if (!in_array('entrada', $tipos)) {
            $tipo = 'entrada';
        } elseif (!in_array('salida', $tipos)) {
            $tipo = 'salida';
        } else {
            // Ya tiene entrada y salida hoy
            return false;
        }

        $sql_insert = "INSERT INTO asistencia_usuarios (id_usuario, codigo, fecha, hora, tipo) 
                       VALUES ('$id_usuario', '$codigo', '$fecha', '$hora', '$tipo')";
        return ejecutarConsulta($sql_insert);
    }

    public function buscarUsuarioPorCodigo($codigo)
    {
        $sql = "SELECT id, nombre, apellidos, login, email, codigo FROM usuarios WHERE codigo = '$codigo'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listar()
    {
        $sql = "SELECT
                    au.id,
                    au.codigo,
                    u.nombre AS nombre_usuario, 
                    u.apellidos AS apellidos_usuario,
                    au.fecha,
                    au.hora,
                    au.tipo
                FROM asistencia_usuarios au
                JOIN usuarios u ON au.id_usuario = u.id
                ORDER BY au.fecha DESC, au.hora DESC";
        return ejecutarConsulta($sql);
    }

    public function listar_reporte($fecha_inicio, $fecha_fin, $id_usuario_o_codigo)
    {
        $sql = "SELECT
                    au.id,
                    au.codigo,
                    u.nombre AS nombre_usuario, 
                    u.apellidos AS apellidos_usuario,
                    au.fecha,
                    au.hora,
                    au.tipo
                FROM asistencia_usuarios au
                JOIN usuarios u ON au.id_usuario = u.id
                WHERE au.fecha >= '$fecha_inicio' AND au.fecha <= '$fecha_fin'";

        if (!empty($id_usuario_o_codigo) && $id_usuario_o_codigo !== "all") {
            if (is_numeric($id_usuario_o_codigo)) {
                $sql .= " AND u.id = '$id_usuario_o_codigo'";
            } else {
                $sql .= " AND u.codigo = '$id_usuario_o_codigo'";
            }
        }
        $sql .= " ORDER BY au.fecha DESC, au.hora DESC";
        return ejecutarConsulta($sql);
    }
}
