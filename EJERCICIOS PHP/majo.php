<?php
// Verificar si los datos fueron enviados a través del método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y sanitizar los datos del formulario
    $nombre = isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '';
    $apellidos = isset($_POST['apellidos']) ? htmlspecialchars($_POST['apellidos']) : '';
    $telefono = isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : '';
    $correo = isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : '';
    $genero = isset($_POST['genero']) ? htmlspecialchars($_POST['genero']) : '';

    // Puedes hacer alguna validación básica aquí (por ejemplo, verificar si los campos no están vacíos)
    if (empty($nombre) || empty($apellidos) || empty($telefono) || empty($correo) || empty($genero)) {
        echo "<p>Todos los campos son obligatorios.</p>";
    } else {
        // Aquí puedes hacer lo que necesites con los datos, como guardarlos en una base de datos
        // O solo mostrar los datos procesados, por ejemplo:
        
        echo "<h2>Datos recibidos:</h2>";
        echo "<p><strong>Nombre:</strong> $nombre</p>";
        echo "<p><strong>Segundo nombre:</strong> $apellidos</p>";
        echo "<p><strong>Teléfono:</strong> $telefono</p>";
        echo "<p><strong>Correo:</strong> $correo</p>";
        echo "<p><strong>Género:</strong> $genero</p>";
    }
} else {
    echo "<p>Formulario no enviado correctamente.</p>";
}
?>
