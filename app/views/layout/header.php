<?php
$mensajeExito = flash('success');
$mensajeError = flash('error');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f8fc">
    <title><?= e(($titulo ?? 'Catálogo') . ' | Nexo Catálogo') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('assets/css/estilos.css')) ?>">
    <script defer src="<?= e(asset('assets/js/app.js')) ?>"></script>
</head>
<body>
<header class="topbar">
    <div class="topbar-inner">
        <a class="brand" href="<?= e(url()) ?>" aria-label="Nexo Catálogo, ir al inicio">
            <span class="brand-mark" aria-hidden="true">N</span>
            <span class="brand-copy"><strong>Nexo</strong><small>CATÁLOGO</small></span>
        </a>
        <nav class="main-nav" aria-label="Navegación principal">
            <a class="nav-link <?= !($modoEdicion ?? false) && !str_contains($_SERVER['QUERY_STRING'] ?? '', 'accion=nuevo') ? 'is-active' : '' ?>" href="<?= e(url()) ?>">Productos</a>
            <a class="nav-link <?= str_contains($_SERVER['QUERY_STRING'] ?? '', 'accion=nuevo') || ($modoEdicion ?? false) ? 'is-active' : '' ?>" href="<?= e(url('nuevo')) ?>">Registrar producto</a>
        </nav>
        <span class="topbar-note"><span class="status-dot"></span> Panel de inventario</span>
    </div>
</header>
<main class="page-shell">
    <?php if ($mensajeExito !== null): ?>
        <div class="notice notice-success" role="status"><span class="notice-icon" aria-hidden="true">✓</span><?= e($mensajeExito) ?></div>
    <?php endif; ?>
    <?php if ($mensajeError !== null): ?>
        <div class="notice notice-error" role="alert"><span class="notice-icon" aria-hidden="true">!</span><?= e($mensajeError) ?></div>
    <?php endif; ?>
