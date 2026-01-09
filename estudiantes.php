<?php include 'php/conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca - Estudiantes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <span class="logo">🎓 Gestión de Estudiantes</span>
    </header>

    <div class="nav-container">
    <a href="index.php" class="nav-btn">Libros</a>
    <a href="estudiantes.php" class="nav-btn">Estudiantes</a>
    <a href="prestamos.php" class="nav-btn">Préstamos Activos</a>
    </div>
    
    <div class="container">
        <div style="background:white; padding:20px; border-radius:8px; max-width:500px; margin:0 auto 30px;">
            <h3 style="margin-top:0">Registrar Nuevo Alumno</h3>
            <form action="php/operaciones.php" method="POST">
                <input type="hidden" name="accion" value="agregar_estudiante">
                <input type="text" name="nombre" placeholder="Nombre Completo" required style="width:90%; padding:10px; margin-bottom:10px;">
                <input type="text" name="grado" placeholder="Grado / Aula" required style="width:90%; padding:10px; margin-bottom:10px;">
                <button type="submit" class="btn btn-prestar" style="width:100%">Guardar Estudiante</button>
            </form>
        </div>

        <h3>Listado de Alumnos</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Grado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM estudiantes ORDER BY id DESC";
                $res = $conn->query($sql);
                while($row = $res->fetch_assoc()){
                    echo "<tr>
                            <td>".$row['id']."</td>
                            <td>".$row['nombre']."</td>
                            <td>".$row['grado']."</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>