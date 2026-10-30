<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/Estudiante.php';

$datos = $_SESSION['estudiante'] ?? null;
if (!is_array($datos)) {
    header('Location: registro.php');
    exit;
}

try {
    $estudiante = new Estudiante(
        (string)($datos['apellidos'] ?? ''),
        (string)($datos['nombres'] ?? ''),
        (string)($datos['fechaNacimiento'] ?? ''),
        (string)($datos['genero'] ?? ''),
        (string)($datos['celular'] ?? ''),
        (string)($datos['carrera'] ?? ''),
        (string)($datos['semestre'] ?? ''),
        (string)($datos['hobby'] ?? '')
    );
} catch (InvalidArgumentException $e) {
    unset($_SESSION['estudiante']);
    header('Location: registro.php');
    exit;
}

function e(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido Estudiante — IESTP Pichanaki</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; }
        body {
            min-height: 100vh; display: flex; justify-content: center; align-items: center;
            padding: 24px; background: #f0f4f8;
        }
        .contenedor {
            width: 100%; max-width: 480px; background: white; border-radius: 18px;
            overflow: hidden; box-shadow: 0 10px 35px #0002;
        }
        .cabecera { padding: 28px 20px; text-align: center; color: white; background: #00897b; }
        .cabecera h1 { font-size: 25px; margin-bottom: 10px; }
        .mensaje {
            margin: 20px; padding: 16px; border-radius: 10px;
            background: #e8f5e9; color: #2e7d32; line-height: 1.6;
        }
        .datos { padding: 0 22px 24px; }
        .datos h2 { font-size: 19px; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 2px solid #e5e7eb; }
        .dato { padding: 11px 0; border-bottom: 1px solid #eee; line-height: 1.5; }
        .dato strong { color: #334155; }
        .acciones { padding: 0 22px 24px; }
        .boton {
            display: block; text-align: center; text-decoration: none; padding: 13px;
            border-radius: 9px; font-weight: bold; background: #00897b; color: white;
        }
        .pie { text-align: center; padding: 16px; background: #f8fafc; color: #64748b; font-size: 12px; }
    </style>
</head>
<body>
<div class="contenedor">
    <div class="cabecera">
        <h1>🎓 ¡Bienvenido/a!</h1>
        <p><?= e($estudiante->getNombreCompleto()) ?></p>
        <p>Nos alegra que formes parte de nuestra institución.</p>
    </div>
    <div class="mensaje">
        <strong>¡Muchos éxitos en esta nueva etapa!</strong><br>
        Tienes <?= $estudiante->obtenerEdad() ?> años.
        ¡Sigue aprendiendo y persiguiendo tus metas!
    </div>
    <div class="datos">
        <h2>Datos del Estudiante</h2>
        <div class="dato">👤 <strong>Apellidos:</strong> <?= e($estudiante->getApellidos()) ?></div>
        <div class="dato">👤 <strong>Nombres:</strong> <?= e($estudiante->getNombres()) ?></div>
        <div class="dato">📅 <strong>Fecha de nacimiento:</strong> <?= e($estudiante->obtenerFechaFormateada()) ?></div>