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
function crearProducto($pdo, $numero_producto, $nombre_producto, $lote_producto, $valor_producto) {
    try {
        $sql = "INSERT INTO productos (numero_producto, nombre_producto, lote_producto, valor_producto) VALUES (:numero_id_producto, :nombre_producto, :lote_producto, :valor_producto)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':numero_id_producto', $numero_id_producto);
        $stmt->bindParam(':nombre_producto', $nombre_producto);
        $stmt->bindParam(':lote_producto', $lote_producto);
        $stmt->bindParam(':valor_producto', $valor_producto);
        $stmt->execute();
        echo "Producto creado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al crear producto: " . $e->getMessage();
    }
}

function leerProducto($pdo, $numero_id_producto) {
    try {
        $sql = "SELECT * FROM productos WHERE numero_id_producto = :numero_id_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':numero_id_producto', $numero_id_producto);
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($productos) {
            foreach ($productos as $producto) {
                echo "ID: " . $producto['numero_id_producto'] . "<br>";
                echo "Nombre: " . $producto['nombre_producto'] . "<br>";
                echo "Lote: " . $producto['lote_producto'] . "<br>";
                echo "Valor: " . $producto['valor_producto'] . "<br><br>";
            }
        } else {
            echo "No se encontraron productos con los datos proporcionados.";
        }
    } catch (PDOException $e) {
        echo "Error al buscar producto: " . $e->getMessage();
    }
}

function actualizarProducto($pdo, $numero_id_producto, $nombre_producto, $lote_producto, $valor_producto) {
    try {
        $sql = "UPDATE productos SET nombre_producto = :nombre_producto, lote_producto = :lote_producto, valor_producto = :valor_producto WHERE numero_id_producto = :numero_id_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':numero_id_producto', $numero_id_producto);
        $stmt->bindParam(':nombre_producto', $nombre_producto);
        $stmt->bindParam(':lote_producto', $lote_producto);
        $stmt->bindParam(':valor_producto', $valor_producto);

        if ($stmt->execute()) {
            echo "Producto actualizado exitosamente.";
        } else {
            echo "Error al actualizar producto.";
        }
    } catch (PDOException $e) {
        echo "Error al actualizar producto: " . $e->getMessage();
    }
}

function eliminarProducto($pdo, $numero_id_producto) {
    try {
        $sql = "DELETE FROM productos WHERE numero_id_producto = :numero_id_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':numero_id_producto', $numero_id_producto);

        if ($stmt->execute()) {
            echo "Producto eliminado exitosamente.";
        } else {
            echo "Error al eliminar producto.";
        }
    } catch (PDOException $e) {
        echo "Error al eliminar producto: " . $e->getMessage();
    }
}

// Manejo de datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'] ?? '';
    $numero_id_producto = htmlspecialchars(trim($_POST['numero_id_producto'] ?? ''));
    $nombre_producto = htmlspecialchars(trim($_POST['nombre_producto'] ?? ''));
    $lote_producto = htmlspecialchars(trim($_POST['lote_producto'] ?? ''));
    $valor_producto = htmlspecialchars(trim($_POST['valor_producto'] ?? ''));

    switch ($accion) {
        case 'create':
            if ($numero_id_producto && $nombre_producto && $lote_producto && $valor_producto) {
                crearProducto($pdo, $numero_id_producto, $nombre_producto, $lote_producto, $valor_producto);
            } else {
                echo "Error: todos los campos son obligatorios para crear un producto.";
            }
            break;

        case 'read':
            leerProducto($pdo, $numero_id_producto);
            break;

        case 'update':
            if ($numero_id_producto && ($nombre_producto || $lote_producto || $valor_producto)) {
                actualizarProducto($pdo, $numero_id_producto, $nombre_producto, $lote_producto, $valor_producto);
            } else {
                echo "Error: el número ID y al menos un dato adicional son obligatorios para actualizar.";
            }
            break;

        case 'delete':
            eliminarProducto($pdo, $numero_id_producto);
            break;

        default:
            echo "Error: acción no reconocida.";
            break;
    }
}
?>
