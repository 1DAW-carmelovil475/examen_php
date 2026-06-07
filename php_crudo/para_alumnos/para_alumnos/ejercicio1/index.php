<?php

    session_start();

    ini_set('display_errors', 1);
    error_reporting(E_ALL);


    if (!isset($_SESSION['colores'])) {
        $_SESSION['colores'] = [];
    }

    if (!isset($_SESSION['total_filas'])) {
        $_SESSION['total_filas'] = 0;
    }

    if(isset($_POST['anadir'])){
        array_push($_SESSION['colores'], $_POST['color']);
        $_SESSION['total_filas']++;
    }elseif(isset($_POST['limpiar'])){
        $_SESSION['colores'] = [];
        $_SESSION['total_filas'] = 0;
        
    }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Filas de Colores</title>
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
            max-width: 800px;
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

        .info {
            background: #34495e;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .badge {
            background: #e74c3c;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 1.1em;
        }

        .form-section {
            padding: 30px;
            background: #ecf0f1;
        }

        .form-group {
            display: flex;
            gap: 15px;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }

        label {
            font-size: 1.1em;
            font-weight: 600;
            color: #2c3e50;
        }

        input[type="color"] {
            width: 80px;
            height: 50px;
            border: 3px solid #2c3e50;
            border-radius: 8px;
            cursor: pointer;
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

        .btn-add {
            background: #27ae60;
            color: white;
        }

        .btn-add:hover {
            background: #229954;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }

        .btn-clear {
            background: #e74c3c;
            color: white;
        }

        .btn-clear:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .rows-section {
            padding: 30px;
            min-height: 200px;
        }

        .rows-title {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.3em;
        }

        .rows-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .color-row {
            height: 50px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .color-row:hover {
            transform: scale(1.02);
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
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🎨 Gestor de Filas de Colores</h1>
            <p>Desarrollo Web en Entorno Servidor - Sesiones PHP</p>
        </header>

        <div class="info">
            <div class="info-item">
                <span>Filas creadas:</span>
                <span class="badge"><?php echo $_SESSION['total_filas']; ?></span>
            </div>
        </div>

        <div class="form-section">
            <form method="post">
                <div class="form-group">
                    <label for="color">Selecciona un color:</label>
                    <input type="color" id="color" name="color" value="#3498db">
                    <button type="submit" name="anadir" class="btn-add">➕ Añadir Fila</button>
                    <button type="submit" name="limpiar" class="btn-clear">🗑️ Limpiar Todo</button>
                </div>
            </form>
        </div>

        <div class="rows-section">
            <h2 class="rows-title">Filas de Colores</h2>
            <div class="rows-container">
                <?php
                    if(empty($_SESSION['colores'])){
                        echo "<p class='empty-message'>Todavia no hay filas. ¡Añade tu primera fila!</p>";
                    }else{
                        foreach($_SESSION['colores'] as $color){
                            echo "<div class='color-row' style='background-color: $color;'></div>";
                        }
                    }
                    
                ?>
            </div>
        </div>

        <footer>
            Examen DWES - Curso 2025/2026
        </footer>
    </div>
</body>
</html>