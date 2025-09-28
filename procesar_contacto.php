<?php
// procesar_contacto.php
// Archivo para procesar el formulario de contacto

// Configuración
$archivo_datos = 'datos/contactos.txt';
$directorio_datos = 'datos';

// Función para sanitizar datos
function sanitizar($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Función para validar email
function validar_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Función para validar teléfono panameño
function validar_telefono($telefono) {
    $patron = '/^(\+507\s?)?\d{4}-?\d{4}$/';
    return preg_match($patron, $telefono);
}

// Verificar que la petición sea POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Inicializar variables y errores
    $errores = array();
    $datos = array();
    
    // Validar y sanitizar campos requeridos
    
    // Nombre
    if (empty($_POST["nombre"])) {
        $errores[] = "El nombre es requerido";
    } else {
        $nombre = sanitizar($_POST["nombre"]);
        if (strlen($nombre) < 2) {
            $errores[] = "El nombre debe tener al menos 2 caracteres";
        } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
            $errores[] = "El nombre solo puede contener letras";
        } else {
            $datos['nombre'] = $nombre;
        }
    }
    
    // Email
    if (empty($_POST["email"])) {
        $errores[] = "El email es requerido";
    } else {
        $email = sanitizar($_POST["email"]);
        if (!validar_email($email)) {
            $errores[] = "Formato de email inválido";
        } else {
            $datos['email'] = $email;
        }
    }
    
    // Teléfono
    if (empty($_POST["telefono"])) {
        $errores[] = "El teléfono es requerido";
    } else {
        $telefono = sanitizar($_POST["telefono"]);
        if (!validar_telefono($telefono)) {
            $errores[] = "Formato de teléfono inválido";
        } else {
            $datos['telefono'] = $telefono;
        }
    }
    
    // Servicio
    if (empty($_POST["servicio"])) {
        $errores[] = "Debe seleccionar un servicio";
    } else {
        $servicio = sanitizar($_POST["servicio"]);
        $servicios_validos = [
            'desarrollo-web',
            'aplicaciones-moviles', 
            'consultoria-it',
            'mantenimiento',
            'e-commerce',
            'sistemas-empresariales',
            'otro'
        ];
        if (!in_array($servicio, $servicios_validos)) {
            $errores[] = "Servicio seleccionado no válido";
        } else {
            $datos['servicio'] = $servicio;
        }
    }
    
    // Mensaje
    if (empty($_POST["mensaje"])) {
        $errores[] = "El mensaje es requerido";
    } else {
        $mensaje = sanitizar($_POST["mensaje"]);
        if (strlen($mensaje) < 20) {
            $errores[] = "El mensaje debe tener al menos 20 caracteres";
        } elseif (strlen($mensaje) > 1000) {
            $errores[] = "El mensaje no puede exceder 1000 caracteres";
        } else {
            $datos['mensaje'] = $mensaje;
        }
    }
    
    // Campos opcionales
    $datos['empresa'] = !empty($_POST["empresa"]) ? sanitizar($_POST["empresa"]) : '';
    $datos['presupuesto'] = !empty($_POST["presupuesto"]) ? sanitizar($_POST["presupuesto"]) : '';
    $datos['fecha_inicio'] = !empty($_POST["fecha_inicio"]) ? sanitizar($_POST["fecha_inicio"]) : '';
    $datos['newsletter'] = isset($_POST["newsletter"]) ? 'Si' : 'No';
    
    // Verificar aceptación de políticas
    if (!isset($_POST["politicas"])) {
        $errores[] = "Debe aceptar los términos y condiciones";
    }
    
    // Si no hay errores, procesar y guardar datos
    if (empty($errores)) {
        
        // Crear directorio de datos si no existe
        if (!file_exists($directorio_datos)) {
            mkdir($directorio_datos, 0755, true);
        }
        
        // Preparar datos para guardar
        $timestamp = date('Y-m-d H:i:s');
        $datos_completos = array_merge(['timestamp' => $timestamp], $datos);
        
        // Formatear datos para el archivo
        $contenido = "\n=== NUEVO CONTACTO ===\n";
        $contenido .= "Fecha: " . $datos_completos['timestamp'] . "\n";
        $contenido .= "Nombre: " . $datos_completos['nombre'] . "\n";
        $contenido .= "Email: " . $datos_completos['email'] . "\n";
        $contenido .= "Teléfono: " . $datos_completos['telefono'] . "\n";
        $contenido .= "Empresa: " . ($datos_completos['empresa'] ?: 'No especificada') . "\n";
        $contenido .= "Servicio: " . $datos_completos['servicio'] . "\n";
        $contenido .= "Presupuesto: " . ($datos_completos['presupuesto'] ?: 'No especificado') . "\n";
        $contenido .= "Fecha Inicio: " . ($datos_completos['fecha_inicio'] ?: 'No especificada') . "\n";
        $contenido .= "Newsletter: " . $datos_completos['newsletter'] . "\n";
        $contenido .= "Mensaje: " . $datos_completos['mensaje'] . "\n";
        $contenido .= "========================\n";
        
        // Guardar en archivo
        if (file_put_contents($archivo_datos, $contenido, FILE_APPEND | LOCK_EX)) {
            
            // Enviar email de notificación (opcional)
            $asunto = "Nuevo contacto desde el sitio web - " . $datos['nombre'];
            $mensaje_email = "Nuevo contacto recibido:\n\n" . $contenido;
            $headers = "From: noreply@techsolutions.pa\r\n";
            $headers .= "Reply-To: " . $datos['email'] . "\r\n";
            
            // Descomentar la siguiente línea para enviar emails
            // mail("info@techsolutions.pa", $asunto, $mensaje_email, $headers);
            
            // Respuesta exitosa
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Mensaje enviado exitosamente'
            ]);
            
        } else {
            // Error al guardar archivo
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error interno del servidor'
            ]);
        }
        
    } else {
        // Hay errores de validación
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'errors' => $errores
        ]);
    }
    
} else {
    // Método no permitido
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}

// Función adicional para leer contactos (para administración)
function leer_contactos($archivo) {
    if (file_exists($archivo)) {
        return file_get_contents($archivo);
    }
    return false;
}

// Función adicional para contar contactos
function contar_contactos($archivo) {
    if (file_exists($archivo)) {
        $contenido = file_get_contents($archivo);
        return substr_count($contenido, '=== NUEVO CONTACTO ===');
    }
    return 0;
}

// Función para exportar contactos en formato CSV (para administración)
function exportar_csv($archivo) {
    if (!file_exists($archivo)) {
        return false;
    }
    
    $contenido = file_get_contents($archivo);
    $contactos = explode('=== NUEVO CONTACTO ===', $contenido);
    
    $csv_content = "Fecha,Nombre,Email,Telefono,Empresa,Servicio,Presupuesto,Fecha_Inicio,Newsletter,Mensaje\n";
    
    foreach ($contactos as $contacto) {
        if (trim($contacto) === '') continue;
        
        $lineas = explode("\n", $contacto);
        $datos_contacto = [];
        
        foreach ($lineas as $linea) {
            if (strpos($linea, ':') !== false) {
                list($campo, $valor) = explode(':', $linea, 2);
                $campo = trim($campo);
                $valor = trim($valor);
                $datos_contacto[$campo] = $valor;
            }
        }
        
        if (!empty($datos_contacto)) {
            $csv_line = '"' . implode('","', [
                $datos_contacto['Fecha'] ?? '',
                $datos_contacto['Nombre'] ?? '',
                $datos_contacto['Email'] ?? '',
                $datos_contacto['Teléfono'] ?? '',
                $datos_contacto['Empresa'] ?? '',
                $datos_contacto['Servicio'] ?? '',
                $datos_contacto['Presupuesto'] ?? '',
                $datos_contacto['Fecha Inicio'] ?? '',
                $datos_contacto['Newsletter'] ?? '',
                str_replace('"', '""', $datos_contacto['Mensaje'] ?? '')
            ]) . '"' . "\n";
            
            $csv_content .= $csv_line;
        }
    }
    
    return $csv_content;
}

// Si se solicita exportación CSV (para administración)
if (isset($_GET['exportar']) && $_GET['exportar'] === 'csv') {
    $csv = exportar_csv($archivo_datos);
    if ($csv) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="contactos_' . date('Y-m-d') . '.csv"');
        echo $csv;
        exit;
    }
}

// Si se solicita ver estadísticas (para administración)
if (isset($_GET['stats'])) {
    $total_contactos = contar_contactos($archivo_datos);
    echo json_encode([
        'total_contactos' => $total_contactos,
        'fecha_ultimo_acceso' => date('Y-m-d H:i:s')
    ]);
    exit;
}
?>