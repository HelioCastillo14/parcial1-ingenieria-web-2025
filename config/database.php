<?php
// Datos de conexión
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "parcial1_ingenieria_web";

// Conectar a MySQL
try {
    $pdo = new PDO("mysql:host=$servidor;dbname=$base_datos", $usuario, $password);
    // echo "Conexión exitosa"; // Descomentar para probar
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>