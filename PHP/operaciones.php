<?php
include 'conexion.php';

// 1. PRESTAR LIBRO (Resta Stock y Crea Registro Activo)
if (isset($_POST['accion']) && $_POST['accion'] == 'prestar') {
    $id_libro = $_POST['id_libro'];
    $id_estudiante = $_POST['id_estudiante'];

    // Verificamos stock real antes de proceder
    $checkStock = $conn->query("SELECT stock FROM libros WHERE id = $id_libro");
    $libro = $checkStock->fetch_assoc();

    if ($libro['stock'] > 0) {
        // Restamos 1 al stock
        $conn->query("UPDATE libros SET stock = stock - 1 WHERE id = $id_libro");
        
        // Registramos el préstamo con fecha y hora actual
        $conn->query("INSERT INTO prestamos (id_libro, id_estudiante, fecha_prestamo, estado) 
                      VALUES ($id_libro, $id_estudiante, NOW(), 'activo')");
    }
    
    header("Location: ../index.php");
    exit();
}

// 2. DEVOLVER LIBRO (Suma Stock y Cierra el Registro con Fecha Final)
if (isset($_GET['accion']) && $_GET['accion'] == 'devolver') {
    $id_libro = $_GET['id'];

    // Recuperamos el libro al inventario
    $conn->query("UPDATE libros SET stock = stock + 1 WHERE id = $id_libro");

    // Actualizamos el registro de préstamo:
    // Ponemos estado 'devuelto' y guardamos el momento exacto en 'fecha_devolucion'
    $conn->query("UPDATE prestamos SET estado = 'devuelto', fecha_devolucion = NOW() 
                  WHERE id_libro = $id_libro AND estado = 'activo' 
                  ORDER BY fecha_prestamo ASC LIMIT 1");

    header("Location: ../index.php");
    exit();
}

// 3. ELIMINAR LIBRO
if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {
    $id_libro = $_GET['id'];
    $conn->query("DELETE FROM libros WHERE id = $id_libro");
    header("Location: ../index.php");
    exit();
}

// 4. AGREGAR ESTUDIANTE
if (isset($_POST['accion']) && $_POST['accion'] == 'agregar_estudiante') {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $grado = mysqli_real_escape_string($conn, $_POST['grado']);
    
    $conn->query("INSERT INTO estudiantes (nombre, grado) VALUES ('$nombre', '$grado')");
    header("Location: ../estudiantes.php");
    exit();
}

// 5. AÑADIR NUEVO LIBRO
if (isset($_POST['accion']) && $_POST['accion'] == 'añadir_libro') {
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $autor = mysqli_real_escape_string($conn, $_POST['autor']);
    $stock = (int)$_POST['stock'];
    $imagen = mysqli_real_escape_string($conn, $_POST['imagen']);

    $conn->query("INSERT INTO libros (titulo, autor, stock, imagen) VALUES ('$titulo', '$autor', $stock, '$imagen')");
    
    header("Location: ../index.php");
    exit();
}
?>