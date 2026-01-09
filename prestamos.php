<?php include 'php/conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Préstamos</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Estilos específicos para esta página */
        .filtros { text-align: center; margin-bottom: 20px; }
        .badge { padding: 5px 10px; border-radius: 15px; font-size: 0.8rem; font-weight: bold; }
        .badge-activo { background: #fff3cd; color: #856404; } /* Amarillo */
        .badge-devuelto { background: #d4edda; color: #155724; } /* Verde */
        .badge-retraso { background: #f8d7da; color: #721c24; } /* Rojo */
    </style>
</head>
<body>
    <header>
        <span class="logo">📋 Registro de Préstamos</span>
    </header>

    <div class="nav-container">
        <a href="index.php" class="nav-btn">Libros</a>
        <a href="estudiantes.php" class="nav-btn">Estudiantes</a>
        <a href="prestamos.php" class="nav-btn active">Préstamos</a>
    </div>

    <div class="container">
        <h1>Control de Préstamos</h1>

        <div class="filtros">
            <a href="prestamos.php?ver=activo" class="nav-btn <?php echo (!isset($_GET['ver']) || $_GET['ver'] == 'activo') ? 'active' : ''; ?>">Pendientes</a>
            <a href="prestamos.php?ver=devuelto" class="nav-btn <?php echo (isset($_GET['ver']) && $_GET['ver'] == 'devuelto') ? 'active' : ''; ?>">Ya Devueltos</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Libro</th>
                    <th>Estudiante</th>
                    <th>Fecha Salida</th>
                    <?php if(isset($_GET['ver']) && $_GET['ver'] == 'devuelto'): ?>
                        <th>Fecha Devolución</th>
                    <?php endif; ?>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $filtro = (isset($_GET['ver']) && $_GET['ver'] == 'devuelto') ? 'devuelto' : 'activo';
                
                $sql = "SELECT p.*, l.titulo, e.nombre 
                        FROM prestamos p
                        JOIN libros l ON p.id_libro = l.id
                        JOIN estudiantes e ON p.id_estudiante = e.id
                        WHERE p.estado = '$filtro'
                        ORDER BY p.fecha_prestamo DESC";
                
                $res = $conn->query($sql);
                if ($res->num_rows > 0) {
                    while($row = $res->fetch_assoc()){
                        $fecha_salida = new DateTime($row['fecha_prestamo']);
                        $hoy = new DateTime();
                        $diff = $fecha_salida->diff($hoy);
                        
                        // Lógica de colores para deudores
                        $clase_badge = ($filtro == 'devuelto') ? 'badge-devuelto' : 'badge-activo';
                        $texto_estado = ($filtro == 'devuelto') ? 'Devuelto' : 'En Posesión';

                        // Si tiene más de 7 días y no se ha devuelto, marcar en rojo
                        if ($filtro == 'activo' && $diff->days > 7) {
                            $clase_badge = 'badge-retraso';
                            $texto_estado = '¡RETRASADO!';
                        }
                ?>
                    <tr>
                        <td><strong><?php echo $row['titulo']; ?></strong></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['fecha_prestamo'])); ?></td>
                        
                        <?php if($filtro == 'devuelto'): ?>
                            <td><?php echo date('d/m/Y', strtotime($row['fecha_devolucion'])); ?></td>
                        <?php endif; ?>

                        <td><span class="badge <?php echo $clase_badge; ?>"><?php echo $texto_estado; ?></span></td>
                    </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay registros en esta sección.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>