<?php
// -- Configuración de Zona Horaria
date_default_timezone_set('America/Lima');

// -- Definir protocolo
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $protocol = "https://";
} else {
    $protocol = "http://";
}

// -- Definir URL base
$base_url =  $protocol . $_SERVER['HTTP_HOST'] . '/test/';
define('BASE_URL', $base_url);
define('DEFAULT_CONTROLLER', 'Login');
define('DEFAULT_LAYOUT', 'layout');

// -- Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PORT', 3306);
define('DB_PASS', '');
define('DB_NAME', 'db_gliese');

// -- Conexión a la base de datos
$conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

// -- Verificar conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// -- Consulta a la base de datos
$sql = "SELECT * FROM products"; 
$result = $conexion->query($sql);  // Ejecutar la consulta SQL

// -- Verificar si hay datos
$data = array();
if ($result->num_rows > 0) {
    while ($item = $result->fetch_assoc()) {
        $data[] = array(
            'id' => $item['id_product'], 
            'code' => $item['code'],
            'id_unit' => $item['id_unit'],
            'unit' => $item['unit'],
            'name' => $item['name'],
            'description' => $item['description'],
            'price' => $item['price'],
            'id_label' => $item['id_label'],
            'label' => $item['label'],
            'status' => $item['status']
        );
    }
} else {
    echo "No hay datos en la tabla.";
}

// -- Verifica el contenido de $data
echo "<pre>";
print_r($data);
echo "</pre>";

exit(); // Detiene la ejecución para ver los datos

// -- Cerrar conexión
$conexion->close();
?>
