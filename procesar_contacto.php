<?php
// Incluir conexión a la base de datos
require_once 'config/database.php';

// Solo procesar si es POST (cuando se envía el formulario)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtener datos del formulario
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $telefono = $_POST["telefono"];
    $empresa = $_POST["empresa"];
    $servicio = $_POST["servicio"];
    $presupuesto = $_POST["presupuesto"];
    $fecha_inicio = $_POST["fecha_inicio"];
    $mensaje = $_POST["mensaje"];
    $newsletter = isset($_POST["newsletter"]) ? 1 : 0;
    
    // Validaciones básicas
    if (empty($nombre) || empty($email) || empty($telefono) || empty($servicio) || empty($mensaje)) {
        echo "Error: Todos los campos requeridos deben estar completos.";
        exit;
    }
    
    // Guardar en base de datos
    try {
        $sql = "INSERT INTO contactos (nombre, email, telefono, empresa, servicio, presupuesto, fecha_inicio, mensaje, newsletter) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $resultado = $stmt->execute([
            $nombre,
            $email,
            $telefono,
            $empresa,
            $servicio,
            $presupuesto,
            $fecha_inicio,
            $mensaje,
            $newsletter
        ]);
        
        if ($resultado) {
            echo "¡Mensaje enviado exitosamente!";
        } else {
            echo "Error al enviar el mensaje.";
        }
        
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    
} else {
    echo "Método no permitido";
}
?>