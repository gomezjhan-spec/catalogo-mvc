<?php
$getValue = static fn (string $key, string $default = ''): string => (string) ($datos[$key] ?? $default);
$accionFormulario = $modoEdicion ? url('actualizar', ['id' => $producto['id']]) : url('crear');
$hayErrores = $errores !== [];
?>
<section class="form-page-heading">
    <a class="back-link" href="<?= e(url()) ?>"><span aria-hidden="true">←</span> Volver al catálogo</a>
    <p class="eyebrow"><?= $modoEdicion ? 'ACTUALIZA LA INFORMACIÓN' : 'NUEVO EN EL CATÁLOGO' ?></p>
    <h1><?= $modoEdicion ? 'Editar producto' : 'Registrar producto' ?></h1>
    <p class="hero-copy">Completa los datos para <?= $modoEdicion ? 'mantener tu catálogo al día' : 'agregarlo a tu inventario' ?>.</p>
</section>

<div class="form-layout">
    <section class="panel form-panel" aria-labelledby="form-title">
        <div class="form-panel-heading"><div class="form-heading-icon" aria-hidden="true">✳</div><div><h2 id="form-title">Información del producto</h2><p>Los campos con <span class="required-mark">*</span> son obligatorios.</p></div></div>

        <?php if ($hayErrores): ?>
            <div class="error-summary" role="alert" aria-labelledby="error-summary-title" tabindex="-1" data-error-summary>
                <strong id="error-summary-title">Revisa estos campos antes de continuar:</strong>
                <ul>
                    <?php foreach ($errores as $campo => $mensaje): ?><li><a href="#<?= e($campo) ?>"><?= e($mensaje) ?></a></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="product-form" method="post" action="<?= e($accionFormulario) ?>">
            <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">
            <div class="form-grid">
                <div class="field-group <?= isset($errores['nombre']) ? 'has-error' : '' ?>">
                    <label for="nombre">Nombre del producto <span class="required-mark">*</span></label>
                    <input id="nombre" name="nombre" type="text" value="<?= e($getValue('nombre')) ?>" minlength="3" maxlength="100" autocomplete="off" required aria-describedby="nombre-help<?= isset($errores['nombre']) ? ' nombre-error' : '' ?>" <?= isset($errores['nombre']) ? 'aria-invalid="true"' : '' ?>>
                    <small id="nombre-help" class="field-help">Entre 3 y 100 caracteres.</small>
                    <?php if (isset($errores['nombre'])): ?><small id="nombre-error" class="field-error"><?= e($errores['nombre']) ?></small><?php endif; ?>
                </div>

                <div class="field-group <?= isset($errores['sku']) ? 'has-error' : '' ?>">
                    <label for="sku">Código del producto (SKU) <span class="required-mark">*</span></label>
                    <input id="sku" name="sku" type="text" value="<?= e($getValue('sku')) ?>" minlength="3" maxlength="20" pattern="[A-Za-z0-9][A-Za-z0-9-]{2,19}" placeholder="Ej. AUD-204" autocomplete="off" autocapitalize="characters" spellcheck="false" required aria-describedby="sku-help<?= isset($errores['sku']) ? ' sku-error' : '' ?>" <?= isset($errores['sku']) ? 'aria-invalid="true"' : '' ?>>
                    <small id="sku-help" class="field-help">Único, de 3 a 20 letras, números o guiones.</small>
                    <?php if (isset($errores['sku'])): ?><small id="sku-error" class="field-error"><?= e($errores['sku']) ?></small><?php endif; ?>
                </div>

                <div class="field-group <?= isset($errores['categoria']) ? 'has-error' : '' ?>">
                    <label for="categoria">Categoría <span class="required-mark">*</span></label>
                    <select id="categoria" name="categoria" required aria-describedby="categoria-help<?= isset($errores['categoria']) ? ' categoria-error' : '' ?>" <?= isset($errores['categoria']) ? 'aria-invalid="true"' : '' ?>>
                        <option value="">Selecciona una categoría</option>
                        <?php foreach (ProductoValidator::CATEGORIAS as $categoria): ?><option value="<?= e($categoria) ?>" <?= $getValue('categoria') === $categoria ? 'selected' : '' ?>><?= e($categoria) ?></option><?php endforeach; ?>
                    </select>
                    <small id="categoria-help" class="field-help">Ayuda a encontrar los productos más rápido.</small>
                    <?php if (isset($errores['categoria'])): ?><small id="categoria-error" class="field-error"><?= e($errores['categoria']) ?></small><?php endif; ?>
                </div>

                <div class="field-group <?= isset($errores['precio']) ? 'has-error' : '' ?>">
                    <label for="precio">Precio de venta <span class="required-mark">*</span></label>
                    <div class="input-prefix"><span aria-hidden="true">$</span><input id="precio" name="precio" type="number" value="<?= e($getValue('precio')) ?>" min="0.01" max="9999999999.99" step="0.01" inputmode="decimal" placeholder="0.00" required aria-describedby="precio-help<?= isset($errores['precio']) ? ' precio-error' : '' ?>" <?= isset($errores['precio']) ? 'aria-invalid="true"' : '' ?>></div>
                    <small id="precio-help" class="field-help">En pesos colombianos (COP), hasta 2 decimales.</small>
                    <?php if (isset($errores['precio'])): ?><small id="precio-error" class="field-error"><?= e($errores['precio']) ?></small><?php endif; ?>
                </div>

                <div class="field-group <?= isset($errores['stock']) ? 'has-error' : '' ?>">
                    <label for="stock">Unidades disponibles <span class="required-mark">*</span></label>
                    <input id="stock" name="stock" type="number" value="<?= e($getValue('stock', '0')) ?>" min="0" max="999999" step="1" inputmode="numeric" required aria-describedby="stock-help<?= isset($errores['stock']) ? ' stock-error' : '' ?>" <?= isset($errores['stock']) ? 'aria-invalid="true"' : '' ?>>
                    <small id="stock-help" class="field-help">Número entero entre 0 y 999.999.</small>
                    <?php if (isset($errores['stock'])): ?><small id="stock-error" class="field-error"><?= e($errores['stock']) ?></small><?php endif; ?>
                </div>

                <div class="field-group <?= isset($errores['activo']) ? 'has-error' : '' ?>">
                    <label for="activo">Visibilidad en el catálogo <span class="required-mark">*</span></label>
                    <select id="activo" name="activo" required aria-describedby="activo-help<?= isset($errores['activo']) ? ' activo-error' : '' ?>" <?= isset($errores['activo']) ? 'aria-invalid="true"' : '' ?>>
                        <option value="1" <?= $getValue('activo', '1') === '1' ? 'selected' : '' ?>>Activo · visible en catálogo</option>
                        <option value="0" <?= $getValue('activo', '1') === '0' ? 'selected' : '' ?>>Inactivo · oculto del catálogo</option>
                    </select>
                    <small id="activo-help" class="field-help">Puedes cambiar este estado cuando quieras.</small>
                    <?php if (isset($errores['activo'])): ?><small id="activo-error" class="field-error"><?= e($errores['activo']) ?></small><?php endif; ?>
                </div>

                <div class="field-group field-full <?= isset($errores['descripcion']) ? 'has-error' : '' ?>">
                    <label for="descripcion">Descripción <span class="optional-label">Opcional</span></label>
                    <textarea id="descripcion" name="descripcion" rows="4" maxlength="500" aria-describedby="descripcion-help<?= isset($errores['descripcion']) ? ' descripcion-error' : '' ?>" <?= isset($errores['descripcion']) ? 'aria-invalid="true"' : '' ?>><?= e($getValue('descripcion')) ?></textarea>
                    <div class="field-meta"><small id="descripcion-help" class="field-help">Agrega detalles que ayuden a identificar el producto.</small><small class="character-count" data-counter="descripcion">0 / 500</small></div>
                    <?php if (isset($errores['descripcion'])): ?><small id="descripcion-error" class="field-error"><?= e($errores['descripcion']) ?></small><?php endif; ?>
                </div>
            </div>

            <?php if (isset($errores['general'])): ?><div id="general" class="notice notice-error form-general-error" role="alert"><?= e($errores['general']) ?></div><?php endif; ?>
            <div class="form-actions"><a class="button button-secondary" href="<?= e(url()) ?>">Cancelar</a><button class="button button-primary" type="submit"><span aria-hidden="true"><?= $modoEdicion ? '✓' : '＋' ?></span> <?= $modoEdicion ? 'Guardar cambios' : 'Guardar producto' ?></button></div>
        </form>
    </section>

    <aside class="form-aside">
        <div class="aside-card aside-tip"><span class="aside-icon" aria-hidden="true">✦</span><h3>Un buen registro empieza aquí</h3><p>Un código claro y una categoría correcta facilitan el control del inventario y la búsqueda de productos.</p></div>
        <div class="aside-card aside-checklist"><h3>Antes de guardar</h3><ul><li><span>✓</span> Revisa el código SKU</li><li><span>✓</span> Confirma el precio y las unidades</li><li><span>✓</span> Elige el estado del producto</li></ul></div>
        <div class="privacy-note"><span aria-hidden="true">⌑</span><p>La información se revisa en el servidor antes de almacenarse.</p></div>
    </aside>
</div>
