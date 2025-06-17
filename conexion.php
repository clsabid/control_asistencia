<?php
// admin/config/conexion.php

// ✅ Mostrar errores PHP (solo para desarrollo, desactívalo en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define tus credenciales de la base de datos
define("DB_HOST", "localhost");       // Tu host de base de datos (ej. 'localhost')
define("DB_NAME", "tu_nombre_de_bd"); // ¡CAMBIA ESTO! El nombre de tu base de datos
define("DB_USERNAME", "tu_usuario_bd"); // ¡CAMBIA ESTO! Tu usuario de base de datos
define("DB_PASSWORD", "tu_contraseña_bd"); // ¡CAMBIA ESTO! Tu contraseña de base de datos
define("DB_ENCODE", "utf8");          // Codificación de la base de datos

// Variable global para la conexión a la base de datos
global $conexion;

// Intentar establecer la conexión
$conexion = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar si hay errores en la conexión
if (mysqli_connect_errno()) {
    // Si hay un error, lo mostramos y terminamos la ejecución
    error_log("Fallo al conectar a MySQL: " . mysqli_connect_error());
    die("Error al conectar con la base de datos. Por favor, contacta al administrador.");
}

// Establecer la codificación de caracteres para la conexión
$conexion->query("SET NAMES '" . DB_ENCODE . "'");

// Función para limpiar cadenas de entrada.
// Importante: Esta función hace un escape básico. Para la máxima seguridad contra inyección SQL,
// DEBES USAR CONSULTAS PREPARADAS en tus modelos, como se ha hecho en Asistencia.php y Empleado.php.
if (!function_exists('limpiarCadena')) {
    function limpiarCadena($cadena){
        global $conexion; // Acceder a la conexión global
        $cadena = trim($cadena); // Eliminar espacios en blanco al inicio y final
        $cadena = htmlspecialchars($cadena, ENT_QUOTES, 'UTF-8'); // Convertir caracteres especiales a entidades HTML
        // Escapar caracteres especiales para usar en consultas SQL.
        // Esto ES NECESARIO si NO UTILIZAS CONSULTAS PREPARADAS para una consulta particular.
        $cadena = $conexion->real_escape_string($cadena); 
        return $cadena;
    }
}

// Función para ejecutar una consulta SQL que no devuelve un solo resultado (ej. INSERT, UPDATE, DELETE, o SELECT con muchos resultados)
if (!function_exists('ejecutarConsulta')) {
    function ejecutarConsulta($sql){
        global $conexion;
        $query = $conexion->query($sql);
        if (!$query) {
            error_log("Error en la consulta: " . $conexion->error . " | SQL: " . $sql);
            return false; // Retorna false en caso de error
        }
        return $query; // Retorna el objeto mysqli_result
    }
}

// Función para ejecutar una consulta SQL que devuelve una sola fila de resultados (ej. SELECT * FROM tabla WHERE id=X)
if (!function_exists('ejecutarConsultaSimpleFila')) {
    function ejecutarConsultaSimpleFila($sql){
        global $conexion;
        $query = $conexion->query($sql);
        if (!$query) {
            error_log("Error en la consulta simple: " . $conexion->error . " | SQL: " . $sql);
            return false; // Retorna false en caso de error
        }
        return $query->fetch_assoc(); // Devuelve un array asociativo o null si no hay resultados
    }
}

// Función para ejecutar una consulta SQL (ej. INSERT) y devolver el ID del último registro insertado
if (!function_exists('ejecutarConsulta_retornarID')) {
    function ejecutarConsulta_retornarID($sql){
        global $conexion;
        $query = $conexion->query($sql);
        if (!$query) {
            error_log("Error en la consulta (retornar ID): " . $conexion->error . " | SQL: " . $sql);
            return false;
        }
        return $conexion->insert_id; // Retorna el ID del último insert
    }
}

// Función auxiliar para bind_param que requiere referencias (útil para call_user_func_array)
if (!function_exists('refValues')) {
    function refValues($arr){
        if (strnatcmp(phpversion(),'5.3') >= 0) // PHP 5.3+
        {
            $refs = array();
            foreach($arr as $key => $value)
                $refs[$key] = &$arr[$key];
            return $refs;
        }
        return $arr;
    }
}

// Nota: La conexión se cierra automáticamente al final del script PHP,
// pero puedes añadir una función para cerrarla explícitamente si lo necesitas.
/*
function cerrarConexion() {
    global $conexion;
    if ($conexion && $conexion->ping()) { // Verificar si la conexión está activa antes de cerrarla
        $conexion->close();
    }
}
*/
?>