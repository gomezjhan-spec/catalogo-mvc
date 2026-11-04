<?php
$totalProductos = count($productos);
$activos = count(array_filter($productos, static fn (array $producto): bool => (int) $producto['activo'] === 1));
$unidades = array_sum(array_map(static fn (array $producto): int => (int) $producto['stock'], $productos));
?>
<section class="hero-row">
    <div>
        <p class="eyebrow">INVENTARIO · RESUMEN</p>
        <h1>Tu catálogo, <span>en orden.</span></h1>
        <p class="hero-copy">Administra la información de tus productos desde un solo lugar.</p>
    </div>
    <a class="button button-primary" href="<?= e(url('nuevo')) ?>"><span aria-hidden="true">＋</span> Registrar producto</a>
</section>

<section class="stats-grid" aria-label="Resumen del inventario">
    <article class="stat-card"><span class="stat-icon icon-violet" aria-hidden="true">▦</span><div><p>Productos encontrados</p><strong><?= $totalProductos ?></strong></div></article>
    <article class="stat-card"><span class="stat-icon icon-green" aria-hidden="true">✓</span><div><p>Activos en catálogo</p><strong><?= $activos ?></strong></div></article>
    <article class="stat-card"><span class="stat-icon icon-amber" aria-hidden="true">▤</span><div><p>Unidades disponibles</p><strong><?= number_format($unidades, 0, ',', '.') ?></strong></div></article>
</section>

<section class="panel catalog-panel">
    <div class="panel-heading">
        <div><h2>Productos</h2><p>Consulta y actualiza los artículos de tu inventario.</p></div>
        <form class="search-form" method="get" action="<?= e(asset('index.php')) ?>" role="search">
            <input type="hidden" name="accion" value="listar">
            <label class="visually-hidden" for="buscar">Buscar productos</label>
            <span class="search-icon" aria-hidden="true">⌕</span>
            <input id="buscar" name="q" type="search" value="<?= e($busqueda) ?>" placeholder="Buscar por nombre o código…" maxlength="100">
            <?php if ($busqueda !== ''): ?><a class="clear-search" href="<?= e(url()) ?>" aria-label="Limpiar búsqueda">×</a><?php endif; ?>
            <button type="submit" class="button button-search">Buscar</button>
        </form>
    </div>

    <?php if ($productos === []): ?>
        <div class="empty-state">
            <span class="empty-illustration" aria-hidden="true">▦</span>
            <?php if ($busqueda !== ''): ?>
                <h3>No encontramos coincidencias</h3>
                <p>Prueba con otro nombre, código o categoría.</p>
                <a class="button button-secondary" href="<?= e(url()) ?>">Ver todo el catálogo</a>
            <?php else: ?>
                <h3>Tu catálogo está listo para empezar</h3>
                <p>Registra el primer producto para verlo aparecer aquí.</p>
                <a class="button button-primary" href="<?= e(url('nuevo')) ?>">Registrar primer producto</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="product-table">
                <thead><tr><th scope="col">Producto</th><th scope="col">Categoría</th><th scope="col">Precio</th><th scope="col">Inventario</th><th scope="col">Estado</th><th scope="col"><span class="visually-hidden">Acciones</span></th></tr></thead>
                <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td data-label="Producto"><div class="product-cell"><span class="product-avatar" aria-hidden="true"><?= e(initial_letter((string) $producto['nombre'])) ?></span><span><strong><?= e($producto['nombre']) ?></strong><small><?= e($producto['sku']) ?></small></span></div></td>
                        <td data-label="Categoría"><span class="category-pill"><?= e($producto['categoria']) ?></span></td>
                        <td data-label="Precio" class="price-cell"><?= e(money($producto['precio'])) ?></td>
                        <td data-label="Inventario"><span class="stock-value <?= (int) $producto['stock'] === 0 ? 'stock-empty' : '' ?>"><?= number_format((int) $producto['stock'], 0, ',', '.') ?> <small>uds.</small></span></td>
                        <td data-label="Estado"><span class="state-pill <?= (int) $producto['activo'] === 1 ? 'state-active' : 'state-inactive' ?>"><span></span><?= (int) $producto['activo'] === 1 ? 'Activo' : 'Inactivo' ?></span></td>
                        <td data-label="Acciones"><div class="row-actions"><a class="icon-button" href="<?= e(url('editar', ['id' => $producto['id']])) ?>" aria-label="Editar <?= e($producto['nombre']) ?>" title="Editar producto">✎</a><form method="post" action="<?= e(url('eliminar')) ?>" data-confirm="¿Eliminar <?= e($producto['nombre']) ?> del catálogo? Esta acción no se puede deshacer."><input type="hidden" name="_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $producto['id'] ?>"><button class="icon-button icon-button-danger" type="submit" aria-label="Eliminar <?= e($producto['nombre']) ?>" title="Eliminar producto">⌫</button></form></div></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="table-foot"><span>Mostrando <?= $totalProductos ?> <?= $totalProductos === 1 ? 'producto' : 'productos' ?><?= $busqueda !== '' ? ' para “' . e($busqueda) . '”' : '' ?></span><span>Los cambios se guardan en MySQL</span></div>
    <?php endif; ?>
</section>

<p class="security-note"><span aria-hidden="true">⌑</span> Tus datos se validan antes de guardarse y se envían de forma segura a la base de datos.</p>
