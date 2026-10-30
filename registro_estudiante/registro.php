<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/Estudiante.php';

$error = '';

if (isset($_GET['nuevo'])) {
    unset($_SESSION['estudiante']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apellidos = trim($_POST['apellidos'] ?? '');
    $nombres = trim($_POST['nombres'] ?? '');
    $fecha = trim($_POST['fecha'] ?? '');
    $genero = trim($_POST['genero'] ?? '');
    $celular = trim($_POST['celular'] ?? '');
    $carrera = trim($_POST['carrera'] ?? '');
    $semestre = trim($_POST['semestre'] ?? '');
    $hobby = trim($_POST['hobby'] ?? '');

    try {
        $estudiante = new Estudiante(
            $apellidos, $nombres, $fecha, $genero,
            $celular, $carrera, $semestre, $hobby
        );
        $_SESSION['estudiante'] = $estudiante->toArray();
        header('Location: bienvenida.php');
        exit;
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    }
}

function valor(string $campo): string
{
    return htmlspecialchars($_POST[$campo] ?? '', ENT_QUOTES, 'UTF-8');
}

$hoy = new DateTimeImmutable('today');
$fechaMinima = $hoy->modify('-65 years')->format('Y-m-d');
$fechaMaxima = $hoy->modify('-17 years')->format('Y-m-d');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro del Estudiante — IESTP Pichanaki</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; }
        body {
            min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 25px;
            background: linear-gradient(135deg, #aed22a, #27a1ca 50%, #f11160);
        }
        .contenedor {
            width: 100%; max-width: 500px; background: white; border-radius: 20px;
            overflow: hidden; box-shadow: 0 18px 50px #0003;
        }
        .cabecera {
            padding: 28px 20px; text-align: center; color: white;
            background: linear-gradient(90deg, #f11f65, #f81185, #167bff);
        }
        .cabecera h1 { font-size: 25px; margin-bottom: 8px; }
        .formulario { padding: 28px; }
        .campo { margin-bottom: 18px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #1e293b; font-size: 14px; }
        input, select {
            width: 100%; padding: 12px 14px; border: 1px solid #cbd5e1;
            border-radius: 10px; font-size: 15px; background: white;
        }
        input:focus, select:focus { outline: 2px solid #f51785; border-color: transparent; }
        .ayuda { display: block; color: #64748b; font-size: 12px; margin-top: 6px; }
        button {
            width: 100%; padding: 14px; border: none; border-radius: 10px;
            background: linear-gradient(90deg, #fe2f67, #f81185, #167bff);
            color: white; font-size: 16px; font-weight: bold; cursor: pointer;
        }
        button:hover { opacity: 0.9; }
        .error { margin: 20px 28px 0; padding: 12px; border-radius: 8px; background: #fee2e2; color: #991b1b; }
        .pie { text-align: center; padding: 18px; background: #f1f5f9; color: #475569; font-size: 13px; }
    </style>
</head>
<body>
<div class="contenedor">
    <div class="cabecera">
        <h1>🎓 Registro del Estudiante</h1>
        <p>IESTP Pichanaki</p>
        <p>Registra tus datos personales</p>
    </div>

    <?php if ($error !== ''): ?>
        <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form class="formulario" method="post" action="registro.php">
       <div class="campo">
            <label for="apellidos">👤 APELLIDOS</label>
            <input 
                type="text" 
                id="apellidos" 
                name="apellidos" 
                placeholder="Ingresa tus apellidos" 
                maxlength="100" 
                required 
                value="<?= valor('apellidos') ?>"
                pattern="[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+"
                title="Solo se permiten letras y espacios — No usar números"
            >
        </div>

        <div class="campo">
            <label for="nombres">👤 NOMBRES</label>
            <input 
                type="text" 
                id="nombres" 
                name="nombres" 
                placeholder="Ingresa tus nombres" 
                maxlength="100" 
                required 
                value="<?= valor('nombres') ?>"
                pattern="[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+"
                title="Solo se permiten letras y espacios — No usar números"
            >
        </div>
        <div class="campo">
            <label for="fecha">📅 FECHA DE NACIMIENTO</label>
            <input type="date" id="fecha" name="fecha" min="<?= $fechaMinima ?>" max="<?= $fechaMaxima ?>" required value="<?= valor('fecha') ?>">
            <small class="ayuda">Edad permitida: de 17 a 65 años inclusive.</small>
        </div>
        <div class="campo">
            <label for="genero">⚧ GENERO</label>
            <select id="genero" name="genero" required>
                <option value="">— Selecciona tu género —</option>
                <option value="Varón" <?= (($_POST['genero'] ?? '') === 'Varón') ? 'selected' : '' ?>>Varón</option>
                <option value="Mujer" <?= (($_POST['genero'] ?? '') === 'Mujer') ? 'selected' : '' ?>>Mujer</option>
            </select>
        </div>
        <div class="campo">
            <label for="celular">📱 NUMERO DE CELULAR</label>
            <input type="tel" id="celular" name="celular" placeholder="Ejemplo: 987654321" pattern="9[0-9]{8}" maxlength="9" minlength="9" required value="<?= valor('celular') ?>">
            <small class="ayuda">Ingresa 9 dígitos y comienza con 9.</small>
        </div>
        <div class="campo">
            <label for="carrera">🎓 CARRERA PROFESIONAL</label>
            <select id="carrera" name="carrera" required>
                <option value="">— Selecciona tu carrera —</option>
                <?php
                $carreras = ['Arquitectura de plataformas y servicios de tecnologias', 'Industrias alimentarias', 'Administración', 'Enfermería', 'Mecánica Automotriz'];
                foreach ($carreras as $opcion):
                ?>
                <option value="<?= htmlspecialchars($opcion, ENT_QUOTES, 'UTF-8') ?>" <?= (($_POST['carrera'] ?? '') === $opcion) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($opcion, ENT_QUOTES, 'UTF-8') ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="campo">
            <label for="semestre">📚 SEMESTRE ACADEMICO</label>
            <select id="semestre" name="semestre" required>
                <option value="">— Selecciona el semestre —</option>
                <?php
                $semestres = ['I semestre', 'II semestre', 'III semestre', 'IV semestre', 'V semestre', 'VI semestre'];
                foreach ($semestres as $opcion):
                ?>
                <option value="<?= htmlspecialchars($opcion, ENT_QUOTES, 'UTF-8') ?>" <?= (($_POST['semestre'] ?? '') === $opcion) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($opcion, ENT_QUOTES, 'UTF-8') ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="campo">
            <label for="hobby">🎮 HOBBY O INTERES</label>
            <input type="text" id="hobby" name="hobby" placeholder="¿Qué te gusta hacer?" maxlength="120" required value="<?= valor('hobby') ?>">
        </div>
        <button type="submit">✨ GENERAR BIENVENIDA</button>
    </form>
    <div class="pie">
        <strong>IESTP Pichanaki</strong><br>Formación profesional<br>Tu futuro comienza aquí<br>Dios te bendiga
    </div>
</div>
</body>
</html>