<?php
// Configuración de la base de datos
$servidor = "localhost";
$usuario = "root";
$password = "";  
$base_datos = "parcial1_ingenieria_web";

try {
    // Crear conexión PDO
    $pdo = new PDO("mysql:host=$servidor;dbname=$base_datos;charset=utf8", $usuario, $password);
    // Configurar PDO para mostrar errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>