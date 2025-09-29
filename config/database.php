<?php
// Configuración de el hosting
$servidor = "sql212.infinityfree.com";
$usuario = "if0_40047424";                    
$password = "3EPLL09Yy0";                     
$base_datos = "if0_40047424_contactos";       

try {
    $pdo = new PDO("mysql:host=$servidor;dbname=$base_datos;charset=utf8", $usuario, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>