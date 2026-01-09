<?php include 'php/conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca - Libros</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <header>
        <span class="logo">📚 BiblioMarta</span>
        <input type="text" id="buscador" class="search-bar" placeholder="Buscar libro o autor...">
    </header>

    <div class="nav-container">
    <a href="index.php" class="nav-btn">Libros</a>
    <a href="estudiantes.php" class="nav-btn">Estudiantes</a>
    <a href="prestamos.php" class="nav-btn">Préstamos Activos</a>
    </div>

    <button class="btn-flotante" onclick="abrirModalAdd()" title="Añadir nuevo libro">
        <i class="fas fa-plus"></i>
    </button>

    <div class="container">
        <h1>Catálogo de Libros</h1>
        <div class="grid-libros">
            <?php
            $sql = "SELECT * FROM libros";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $img = "img/" . $row['imagen']; 
                    $stock = $row['stock'];
                    $agotado = ($stock <= 0);
            ?>
                <div class="card">
                    <img src="<?php echo $img; ?>" alt="Portada" onerror="this.src='IMG/LIBRO.jpg'">
                    
                    <div class="info">
                        <h3 class="titulo"><?php echo $row['titulo']; ?></h3>
                        <p class="autor"><?php echo $row['autor']; ?></p>
                        
                        <p class="stock">
                            <?php if($agotado): ?>
                                <span class="agotado-txt">¡AGOTADO!</span>
                            <?php else: ?>
                                Disponibles: <strong><?php echo $stock; ?></strong>
                            <?php endif; ?>
                        </p>

                        <div class="acciones">
                            <button class="btn btn-prestar" 
                                onclick="abrirModal(<?php echo $row['id']; ?>, '<?php echo $row['titulo']; ?>')" 
                                <?php if($agotado) echo 'disabled'; ?>>
                                <i class="fas fa-hand-holding"></i> &nbsp; Prestar
                            </button>

                            <a href="php/operaciones.php?accion=devolver&id=<?php echo $row['id']; ?>" class="btn btn-guardar" title="Devolver Libro">
                                <i class="fas fa-save"></i>
                            </a>

                            <a href="javascript:void(0);" onclick="confirmarBorrado(<?php echo $row['id']; ?>)" class="btn btn-eliminar" title="Eliminar Libro">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php
                }
            } else { echo "<p style='text-align:center; width:100%'>No hay libros registrados.</p>"; }
            ?>
        </div>
    </div>

    <div id="modalPrestamo" class="modal">
        <div class="modal-content">
            <h3 id="tituloModal">Prestar Libro</h3>
            <form action="php/operaciones.php" method="POST">
                <input type="hidden" name="accion" value="prestar">
                <input type="hidden" name="id_libro" id="idLibroInput">
                <label>Selecciona al estudiante:</label>
                <select name="id_estudiante" required>
                    <option value="">-- Elegir alumno --</option>
                    <?php
                    $est = $conn->query("SELECT * FROM estudiantes ORDER BY nombre ASC");
                    while($e = $est->fetch_assoc()){
                        echo "<option value='".$e['id']."'>".$e['nombre']." (".$e['grado'].")</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-prestar" style="width:100%; margin-top:10px;">Confirmar</button>
                <button type="button" class="btn" style="width:100%; margin-top:5px; background:#ccc; color:#333" onclick="cerrarModal()">Cancelar</button>
            </form>
        </div>
    </div>

    <div id="modalAddLibro" class="modal">
        <div class="modal-content">
            <h3>Añadir Nuevo Libro</h3>
            <form action="php/operaciones.php" method="POST">
                <input type="hidden" name="accion" value="añadir_libro">
                <input type="text" name="titulo" placeholder="Título del libro" required style="width:90%; padding:10px; margin-bottom:10px;">
                <input type="text" name="autor" placeholder="Autor" required style="width:90%; padding:10px; margin-bottom:10px;">
                <input type="number" name="stock" placeholder="Cantidad inicial" required style="width:90%; padding:10px; margin-bottom:10px;">
                
                <p style="font-size: 11px; color: #666; text-align: left; margin: 0 5%;">Escribe el nombre del archivo (ej: libro1.jpg):</p>
                <input type="text" name="imagen" placeholder="nombre_archivo.jpg" required style="width:90%; padding:10px; margin-bottom:10px;">
                
                <button type="submit" class="btn btn-guardar" style="width:100%">Guardar Libro</button>
                <button type="button" class="btn" style="width:100%; margin-top:5px; background:#ccc; color:#333" onclick="cerrarModalAdd()">Cancelar</button>
            </form>
        </div>
    </div>

    <script src="js/main.js"></script>
    <script>
        const modalPrestamo = document.getElementById('modalPrestamo');
        const modalAdd = document.getElementById('modalAddLibro');
        
        // Funciones para el Préstamo
        function abrirModal(id, titulo) {
            document.getElementById('idLibroInput').value = id;
            document.getElementById('tituloModal').innerText = "Prestar: " + titulo;
            modalPrestamo.style.display = 'flex';
        }
        function cerrarModal() { modalPrestamo.style.display = 'none'; }

        // Funciones para Añadir Libro
        function abrirModalAdd() { modalAdd.style.display = 'flex'; }
        function cerrarModalAdd() { modalAdd.style.display = 'none'; }

        function confirmarBorrado(id) {
            if(confirm("¿Seguro que quieres eliminar este libro del sistema?")) {
                window.location.href = "php/operaciones.php?accion=eliminar&id=" + id;
            }
        }

        // Cerrar modales si se hace clic fuera de ellos
        window.onclick = function(event) {
            if (event.target == modalPrestamo) cerrarModal();
            if (event.target == modalAdd) cerrarModalAdd();
        }
    </script>
</body>
</html>