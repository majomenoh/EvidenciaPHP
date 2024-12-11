<?php
// Configuración de conexión a la base de datos
$host = 'localhost';
$dbname = 'eps';
$usuario = 'root';
$contraseña = '';

try {
    // Crear una nueva conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $contraseña);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("¡Error en la conexión a la base de datos!: " . $e->getMessage());
}

// Funciones CRUD
function crearFactura($pdo, $numero_factura, $id_cliente, $id_producto, $cantidad, $valor_total) {
    try {
        $sql = "INSERT INTO facturas (numero_factura, id_cliente, id_producto, cantidad, valor_total) VALUES ( :numero_factura, :id_cliente, :id_producto, :cantidad, :valor_total)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':numero_factura', $numero_factura);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':valor_total', $valor_total);
        $stmt->execute();
        echo "Factura creada exitosamente.";
    } catch (PDOException $e) {
        echo "Error al crear factura: " . $e->getMessage();
    }
}

function leerFactura($pdo, $numero_id_factura) {
    try {
        $sql = "SELECT * FROM facturas WHERE numero_id_factura = :numero_id_factura";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':numero_id_factura', $numero_id_factura);
        $stmt->execute();
        $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($facturas) {
            foreach ($facturas as $factura) {
                echo "ID: " . $factura['numero_id_factura'] . "<br>";
                echo "Número de Factura: " . $factura['numero_factura'] . "<br>";
                echo "ID de Cliente: " . $factura['id_cliente'] . "<br>";
                echo "ID de Producto: " . $factura['id_producto'] . "<br>";
                echo "Cantidad: " . $factura['cantidad'] . "<br><br>";
            }
        } else {
            echo "No se encontraron facturas con los datos proporcionados.";
        }
    } catch (PDOException $e) {
        echo "Error al buscar factura: " . $e->getMessage();
    }
}

function actualizarFactura($pdo, $numero_factura, $id_cliente, $id_producto, $cantidad) {
    try {
        $sql = "UPDATE facturas SET numero_factura = :numero_factura, id_cliente = :id_cliente, id_producto = :id_producto, cantidad = :cantidad WHERE numero_factura = :numero_factura";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':numero_factura', $numero_factura);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->bindParam(':cantidad', $cantidad);

        if ($stmt->execute()) {
            echo "Factura actualizada exitosamente.";
        } else {
            echo "Error al actualizar factura.";
        }
    } catch (PDOException $e) {
        echo "Error al actualizar factura: " . $e->getMessage();
    }
}

function eliminarFactura($pdo, $numero_id_factura) {
    try {
        $sql = "DELETE INTO facturas (numero_factura, id_cliente, id_producto, cantidad, valor_total) VALUES ( :numero_factura, :id_cliente, :id_producto, :cantidad, :valor_total)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':numero_factura', $numero_factura);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':valor_total', $valor_total);
        $stmt->execute();
        echo "Factura eliminada exitosamente.";
    } catch (PDOException $e) {
        echo "Error al eliminar factura: " . $e->getMessage();
    }
}

// Manejo de datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'] ?? '';
    $numero_id_factura = htmlspecialchars(trim($_POST['numero_id_factura'] ?? ''));
    $numero_factura = htmlspecialchars(trim($_POST['numero_factura'] ?? ''));
    $id_cliente = htmlspecialchars(trim($_POST['id_cliente'] ?? ''));
    $id_producto = htmlspecialchars(trim($_POST['id_producto'] ?? ''));
    $cantidad = htmlspecialchars(trim($_POST['cantidad'] ?? ''));
    $valor_total = htmlspecialchars(trim($_POST['valor_total'] ?? ''));
    switch ($accion) {
        case 'create':
            if ( $numero_factura && $id_cliente && $id_producto && $cantidad &&$valor_total) {
                crearFactura($pdo, $numero_factura, $id_cliente, $id_producto, $cantidad, $valor_total);
            } else {
                echo "Error: todos los campos son obligatorios para crear una factura.";
            }
            break;

        case 'read':
            leerFactura($pdo, $numero_id_factura);
            break;

        case 'update':
            if ($numero_id_factura && ($numero_factura || $id_cliente || $id_producto || $cantidad)) {
                actualizarFactura($pdo, $numero_factura, $numero_factura, $id_cliente, $id_producto, $cantidad);
            } else {
                echo "Error: el número ID y al menos un dato adicional son obligatorios para actualizar.";
            }
            break;

        case 'delete':
            eliminarFactura($pdo, $numero_factura);
            break;

        default:
            echo "Error: acción no reconocida.";
            break;
    }
}
?>
