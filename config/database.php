<?php
// Configuración para InfinityFree
$servidor = "sql212.infinityfree.com";
$usuario = "if0_40047424_[tu-usuario]";  // Reemplazar con tu usuario real
$password = "3EPLLO9Yy0";             // Reemplazar con tu password real
$base_datos = "if0_40047424_parcial_web"; // Reemplazar con nombre real de BD

try {
    $pdo = new PDO("mysql:host=$servidor;dbname=$base_datos;charset=utf8", $usuario, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>