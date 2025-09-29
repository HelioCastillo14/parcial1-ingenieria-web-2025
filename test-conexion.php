<?php
require_once 'config/database.php';

echo "<h1>Prueba de Conexión</h1>";
echo "<p>Si ves este mensaje, la conexión funciona!</p>";

// Contar contactos
try {
    $sql = "SELECT COUNT(*) as total FROM contactos";
    $stmt = $pdo->query($sql);
    $resultado = $stmt->fetch();
    echo "<p>Contactos en la base de datos: " . $resultado['total'] . "</p>";
} catch(PDOException $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>