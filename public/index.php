<?php
declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

try {
    $controller = new ProductoController(Database::connection());
    $controller->despachar((string) ($_GET['accion'] ?? 'listar'));
} catch (PDOException $exception) {
    error_log((string) $exception);
    http_response_code(503);
    $titulo = 'Base de datos no disponible';
    require dirname(__DIR__) . '/app/views/layout/header.php';
    require dirname(__DIR__) . '/app/views/errores/database.php';
    require dirname(__DIR__) . '/app/views/layout/footer.php';
}
