<?php
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contactos</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Lista de Contactos</h1>
    
    <?php
    try {
        $sql = "SELECT * FROM contactos ORDER BY id DESC";
        $stmt = $pdo->query($sql);
        $contactos = $stmt->fetchAll();
        
        if (count($contactos) > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Servicio</th><th>Fecha</th></tr>";
            
            foreach($contactos as $contacto) {
                echo "<tr>";
                echo "<td>" . $contacto['id'] . "</td>";
                echo "<td>" . $contacto['nombre'] . "</td>";
                echo "<td>" . $contacto['email'] . "</td>";
                echo "<td>" . $contacto['telefono'] . "</td>";
                echo "<td>" . $contacto['servicio'] . "</td>";
                echo "<td>" . $contacto['fecha_creacion'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay contactos aún.</p>";
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    ?>
    
    <br>
    <a href="contacto.html">Volver al formulario</a>
</body>
</html>