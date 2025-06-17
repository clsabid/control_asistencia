<?php
// modelos/Asistencia.php

// Asegúrate de que las rutas sean correctas para tu configuración
require_once __DIR__ . '/../admin/config/conexion.php'; 
require_once __DIR__ . '/../modelos/Empleado.php'; // Necesario para buscar datos del empleado

class Asistencia
{
    public function __construct() {}

    /**
     * Registra la asistencia (entrada o salida) de un usuario.
     * Determina automáticamente si es una entrada o salida basada en registros existentes para el día.
     * Utiliza consultas preparadas para mayor seguridad.
     *
     * @param int $id_usuario El ID del empleado.
     * @param string $codigo El código del empleado.
     * @return bool True si la asistencia se registró correctamente, false en caso contrario.
     */
    public function registrarAsistencia($id_usuario, $codigo)
    {
        global $conexion; // Accede a la conexión global
        $fecha = date("Y-m-d");
        $hora = date("H:i:s");

        // 1. Revisar si ya hay registros hoy para este usuario
        // USANDO CONSULTA PREPARADA PARA SEGURIDAD
        $sql_check = "SELECT tipo FROM asistencia 
                      WHERE id_usuario = ? AND fecha = ? 
                      ORDER BY hora ASC";
        
        if ($stmt_check = $conexion->prepare($sql_check)) {
            $stmt_check->bind_param("is", $id_usuario, $fecha); // "i" para int, "s" para string
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            $tipos = [];
            while ($row = $result_check->fetch_assoc()) {
                $tipos[] = $row['tipo'];
            }
            $stmt_check->close();
        } else {
            error_log("Error al preparar la consulta de verificación de asistencia: " . $conexion->error);
            return false; // Error en la preparación de la consulta
        }

        // Determinar si es entrada o salida
        $tipo = '';
        if (!in_array('entrada', $tipos)) {
            $tipo = 'entrada';
        } elseif (!in_array('salida', $tipos)) {
            $tipo = 'salida';
        } else {
            // Ya tiene entrada y salida hoy, no se puede registrar más
            return false;
        }

        // 2. Obtener el nombre completo del empleado usando el modelo Empleado
        $empleadoModel = new Empleado();
        $empleado_data = $empleadoModel->mostrar($id_usuario); 
        $nombre_completo_empleado = 'Desconocido'; // Valor por defecto
        if ($empleado_data && isset($empleado_data['nombre']) && isset($empleado_data['apellidos'])) {
            $nombre_completo_empleado = $empleado_data['nombre'] . ' ' . $empleado_data['apellidos'];
        }

        // 3. Insertar el registro de asistencia
        // USANDO CONSULTA PREPARADA PARA SEGURIDAD
        $sql_insert = "INSERT INTO asistencia (id_usuario, codigo, empleado, fecha, hora, tipo) 
                       VALUES (?, ?, ?, ?, ?, ?)";
        
        if ($stmt_insert = $conexion->prepare($sql_insert)) {
            // "isssss" -> int, string, string, string, string, string
            $stmt_insert->bind_param("isssss", $id_usuario, $codigo, $nombre_completo_empleado, $fecha, $hora, $tipo);
            $insert_result = $stmt_insert->execute();
            if (!$insert_result) {
                error_log("Error al insertar asistencia: " . $stmt_insert->error);
            }
            $stmt_insert->close();
            return $insert_result;
        } else {
            error_log("Error al preparar la consulta de inserción de asistencia: " . $conexion->error);
            return false; // Error en la preparación de la consulta
        }
    }

    /**
     * Busca un usuario (empleado) por su código en la tabla 'empleado'.
     * Utiliza consultas preparadas para mayor seguridad.
     *
     * @param string $codigo El código del empleado.
     * @return array|false Un array asociativo con los datos del empleado o false si no se encuentra o hay un error.
     */
    public function buscarUsuarioPorCodigo($codigo)
    {
        global $conexion; // Accede a la conexión global
        // USANDO CONSULTA PREPARADA PARA SEGURIDAD
        $sql = "SELECT id, nombre, apellidos, documento_numero, telefono, codigo FROM empleado WHERE codigo = ?";
        
        if ($stmt = $conexion->prepare($sql)) {
            $stmt->bind_param("s", $codigo); // "s" para string
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc(); // Obtiene la fila como un array asociativo
            $stmt->close(); // Cierra el statement
            return $data; // Retorna los datos del empleado o null
        } else {
            error_log("Error al preparar la consulta buscarUsuarioPorCodigo: " . $conexion->error);
            return false; // Retorna false en caso de error
        }
    }

    /**
     * Lista todas las asistencias registradas.
     *
     * @return mysqli_result|false Objeto mysqli_result con los datos de asistencia o false en caso de error.
     */
    public function listar()
    {
        // Esta consulta no tiene parámetros variables, por lo que ejecutarConsulta es suficiente.
        $sql = "SELECT
                    id,
                    codigo,
                    empleado,
                    fecha,
                    hora,
                    tipo
                FROM asistencia
                ORDER BY fecha DESC, hora DESC"; // Ordenar para que lo más reciente aparezca primero
        
        $resultado = ejecutarConsulta($sql);
        if (!$resultado) {
            global $conexion;
            error_log("Error al ejecutar consulta en listar(): " . $conexion->error);
            return false;
        }
        return $resultado;
    }

    /**
     * Genera un reporte de asistencias filtrado por rango de fechas y/o empleado/código.
     * Utiliza consultas preparadas para mayor seguridad.
     *
     * @param string $fecha_inicio Fecha de inicio del reporte (formato 'YYYY-MM-DD').
     * @param string $fecha_fin Fecha de fin del reporte (formato 'YYYY-MM-DD').
     * @param string $id_usuario_o_codigo ID o código del empleado para filtrar, o "all" para todos.
     * @return mysqli_result|false Objeto mysqli_result con los datos del reporte o false en caso de error.
     */
    public function listar_reporte($fecha_inicio, $fecha_fin, $id_usuario_o_codigo)
    {
        global $conexion; // Accede a la conexión global

        $sql = "SELECT
                    id,
                    codigo,
                    empleado,
                    fecha,
                    hora,
                    tipo
                FROM asistencia 
                WHERE fecha >= ? AND fecha <= ?";
        
        $params = [$fecha_inicio, $fecha_fin];
        $types = "ss"; // Ambos parámetros de fecha son strings

        if (!empty($id_usuario_o_codigo) && $id_usuario_o_codigo !== "all") {
            // Determina si el filtro es por ID de usuario (numérico) o por código (string)
            if (is_numeric($id_usuario_o_codigo)) {
                $sql .= " AND id_usuario = ?"; // Filtra por ID de usuario en la tabla 'asistencia'
                $params[] = (int)$id_usuario_o_codigo; // Asegura que sea entero
                $types .= "i"; // "i" para entero
            } else {
                $sql .= " AND codigo = ?"; // Filtra por código en la tabla 'asistencia'
                $params[] = $id_usuario_o_codigo;
                $types .= "s"; // "s" para string
            }
        }
        $sql .= " ORDER BY fecha DESC, hora DESC"; // Ordena el reporte

        // USANDO CONSULTA PREPARADA PARA SEGURIDAD
        if ($stmt = $conexion->prepare($sql)) {
            // Usa la función auxiliar refValues para pasar los parámetros por referencia a bind_param
            $bind_params = array_merge([$types], $params);
            call_user_func_array([$stmt, 'bind_param'], refValues($bind_params));

            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return $result;
        } else {
            error_log("Error al preparar la consulta listar_reporte: " . $conexion->error);
            return false;
        }
    }
}
?>