<?php

    
    var_dump($_POST);

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=registro_notas;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if(isset($_POST['nombre'], $_POST['asignatura'], $_POST['nota'])){
            $nombre = $_POST['nombre'];
            $asignatura  = $_POST['asignatura'];
            $nota = $_POST['nota'];

            $sql_insert = "INSERT INTO notas (nombre_alumno, asignatura, nota) VALUES (?, ?, ?)";

            if(isset($_POST['submit'])){
                if($nota >= 0 && $nota <= 10){
                    $stmt = $pdo->prepare($sql_insert);
                    $stmt->execute([$nombre, $asignatura, $nota]);
                }else{
                    echo "<p>La nota debe de ser entre 0 y 10</p>";
                }
                    
            }

        }

        if(!empty($_GET['filtro'])){
            $sql_select = "SELECT * FROM notas WHERE asignatura = ?";
            $stmt=$pdo->prepare($sql_select);
            $stmt->execute([$_GET['filtro']]);
        }else{
            $sql_select = "SELECT * FROM notas";
            $stmt=$pdo->prepare($sql_select);
            $stmt->execute();
        }

        if(!empty($_GET['filtro'])){
            $sql_stats = "SELECT COUNT(*) as total, AVG(nota) as media FROM notas WHERE asignatura = ?";
            $stmt_stats = $pdo->prepare($sql_stats);
            $stmt_stats->execute([$_GET['filtro']]);
        }else{
            $sql_stats = "SELECT COUNT(*) as total, AVG(nota) as media FROM notas";
            $stmt_stats = $pdo->prepare($sql_stats);
            $stmt_stats->execute();
        }

        $stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);

        $notas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Notas - DWES</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        header {
            background: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
        }

        header h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .stats-section {
            background: #34495e;
            padding: 20px 30px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            color: white;
            flex-wrap: wrap;
            gap: 20px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-label {
            font-size: 0.9em;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 2em;
            font-weight: bold;
            color: #3498db;
        }

        .form-section {
            padding: 30px;
            background: #ecf0f1;
        }

        .form-section h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.5em;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 0.95em;
        }

        input[type="text"],
        input[type="number"],
        select {
            padding: 12px;
            border: 2px solid #bdc3c7;
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        button {
            padding: 12px 30px;
            font-size: 1em;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit {
            background: #27ae60;
            color: white;
        }

        .btn-submit:hover {
            background: #229954;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }

        .filter-section {
            padding: 20px 30px;
            background: #f8f9fa;
            border-top: 2px solid #e0e0e0;
            border-bottom: 2px solid #e0e0e0;
        }

        .filter-section h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.2em;
        }

        .filter-controls {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-filter {
            background: #3498db;
            color: white;
        }

        .btn-filter:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }

        .table-section {
            padding: 30px;
        }

        .table-section h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.5em;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        thead {
            background: #34495e;
            color: white;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 0.95em;
            text-transform: uppercase;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #ecf0f1;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .nota-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: bold;
            color: white;
            min-width: 50px;
            text-align: center;
        }

        .nota-alta {
            background: #27ae60;
        }

        .nota-media {
            background: #f39c12;
        }

        .nota-baja {
            background: #e74c3c;
        }

        .empty-message {
            text-align: center;
            color: #95a5a6;
            font-style: italic;
            padding: 40px;
        }

        footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 0.9em;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 0.9em;
            }

            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📚 Registro de Notas del Curso</h1>
            <p>Desarrollo Web en Entorno Servidor - Gestión de Calificaciones</p>
        </header>

        <div class="stats-section">
            <div class="stat-item">
                <div class="stat-label">Total de Notas</div>
                <div class="stat-value"><?php echo $stats['total'] ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Nota Media</div>
                <div class="stat-value"><?php echo number_format($stats['media'], 2) ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Filtro Activo</div>
                <div class="stat-value">
                    <?php
                        if(!empty($_GET['filtro'])){
                            echo $_GET['filtro'];
                        }else{
                            echo "Todas";
                        }
                    ?>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2>➕ Añadir Nueva Nota</h2>
            <form method="POST" action="">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nombre">Nombre del Alumno</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Ej: Juan Pérez García" required>
                    </div>
                    <div class="form-group">
                        <label for="asignatura">Asignatura</label>
                        <select id="asignatura" name="asignatura" required>
                            <option value="">Selecciona una asignatura</option>
                            <option value="DWES">DWES - Desarrollo Web en Entorno Servidor</option>
                            <option value="DWEC">DWEC - Desarrollo Web en Entorno Cliente</option>
                            <option value="DIW">DIW - Diseño de Interfaces Web</option>
                            <option value="DAW">DAW - Despliegue de Aplicaciones Web</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nota">Nota (0-10)</label>
                        <input type="number" id="nota" name="nota" min="0" max="10" step="0.01" placeholder="Ej: 8.5" required>
                    </div>
                </div>
                <div class="button-group">
                    <button type="submit" name="submit" class="btn-submit">✓ Guardar Nota</button>
                </div>
            </form>
        </div>

        <div class="filter-section">
            <h3>🔍 Filtrar Notas por Asignatura</h3>
            <form method="GET" action="">
                <div class="filter-controls">
                    <select name="filtro" id="filtro">
                        <option value="">Todas las asignaturas</option>
                        <option value="DWES">DWES - Desarrollo Web en Entorno Servidor</option>
                        <option value="DWEC">DWEC - Desarrollo Web en Entorno Cliente</option>
                        <option value="DIW">DIW - Diseño de Interfaces Web</option>
                        <option value="DAW">DAW - Despliegue de Aplicaciones Web</option>
                    </select>
                    <button type="submit" class="btn-filter">Aplicar Filtro</button>
                </div>
            </form>
        </div>

        <div class="table-section">
            <h2>📊 Listado de Notas</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Alumno</th>
                        <th>Asignatura</th>
                        <th>Nota</th>
                        <th>Fecha Registro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($notas as $fila): ?>
                        <tr>
                            <td><?php echo $fila['id']; ?></td>
                            <td><?php echo $fila['nombre_alumno']; ?></td>
                            <td><?php echo $fila['asignatura']; ?></td>
                            <td>
                                <?php
                                if($fila['nota'] >= 8){
                                    $clase = 'nota-alta';
                                }elseif($fila['nota'] >= 6){
                                    $clase = 'nota-media';
                                }else{
                                    $clase = 'nota-baja';
                                }
                                ?>
                                <span class="nota-badge <?php echo $clase; ?>"><?php echo number_format($fila['nota'], 2); ?></span>
                            </td>
                            <td><?php echo $fila['fecha_registro']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <footer>
            Examen DWES - Curso 2025/2026
        </footer>
    </div>
</body>
</html>