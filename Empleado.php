<?php
// modelos/Empleado.php

// Asegúrate de que esta ruta sea correcta para tu configuración
require_once __DIR__ . '/../admin/config/conexion.php'; 

class Empleado
{
    public function __construct() {}

    /**
     * Obtiene los datos de un empleado por su ID.
     * Utiliza consultas preparadas para mayor seguridad.
     *
     * @param int $idempleado El ID del empleado.
     * @return array|false Un array asociativo con los datos del empleado o false si no se encuentra o hay un error.
     */
    public function mostrar($idempleado)
    {
        global $conexion; // Accede a la conexión global

        // Consulta preparada para mayor seguridad y eficiencia
        $sql = "SELECT id, nombre, apellidos, documento_numero, telefono, email, codigo 
                FROM empleado 
                WHERE id = ?";
        
        if ($stmt = $conexion->prepare($sql)) {
            // "i" indica que el parámetro $idempleado es de tipo entero
            $stmt->bind_param("i", $idempleado); 
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc(); // Obtiene la fila como un array asociativo
            $stmt->close(); // Cierra el statement
            return $data; // Retorna los datos del empleado o null si no se encuentra
        } else {
            // Registra el error si la preparación de la consulta falla
            error_log("Error al preparar la consulta en Empleado::mostrar: " . $conexion->error);
            return false; // Retorna false en caso de error
        }
    }

    // Puedes añadir aquí otros métodos relacionados con empleados (ej. listar, insertar, actualizar, eliminar)
    /*
    public function listar() {
        global $conexion;
        $sql = "SELECT id, nombre, apellidos, codigo FROM empleado ORDER BY nombre ASC";
        return ejecutarConsulta($sql); // Utiliza la función de conexión para consultas simples
    }

    public function insertar($nombre, $apellidos, $documento_numero, $telefono, $email, $codigo) {
        global $conexion;
        $sql = "INSERT INTO empleado (nombre, apellidos, documento_numero, telefono, email, codigo, condicion)
                VALUES (?, ?, ?, ?, ?, ?, 1)"; // 'condicion' 1 por defecto activo
        if ($stmt = $conexion->prepare($sql)) {
            $stmt->bind_param("ssssss", $nombre, $apellidos, $documento_numero, $telefono, $email, $codigo);
            $rpta = $stmt->execute();
            $stmt->close();
            return $rpta;
        } else {
            error_log("Error al preparar INSERT en Empleado: " . $conexion->error);
            return false;
        }
    }
    */
}
?>