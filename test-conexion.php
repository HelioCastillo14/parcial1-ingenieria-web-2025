<?php
// Incluir archivo de conexión
require_once 'config/database.php';

echo "<h1>Prueba de Conexión MySQL</h1>";

try {
    // Probar conexión
    echo "<p style='color: green;'>✅ Conexión exitosa a la base de datos!</p>";
    
    // Probar que existe la tabla
    $sql = "SHOW TABLES";
    $stmt = $pdo->query($sql);
    $tablas = $stmt->fetchAll();
    
    echo "<h3>Tablas en la base de datos:</h3>";
    echo "<ul>";
    foreach($tablas as $tabla) {
        echo "<li>" . $tabla[0] . "</li>";
    }
    echo "</ul>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>