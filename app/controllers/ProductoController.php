<?php
declare(strict_types=1);

final class ProductoController
{
    private Producto $productos;
    private ProductoValidator $validator;

    public function __construct(PDO $db)
    {
        $this->productos = new Producto($db);
        $this->validator = new ProductoValidator();
    }

    public function despachar(string $accion): void
    {
        switch ($accion) {
            case 'listar':
                $this->listar();
                return;
            case 'nuevo':
                $this->formulario();
                return;
            case 'crear':
                $this->crear();
                return;
            case 'editar':
                $this->formulario($this->idSolicitado());
                return;
            case 'actualizar':
                $this->actualizar();
                return;
            case 'eliminar':
                $this->eliminar();
                return;
            default:
                $this->noEncontrado();
        }
    }

    private function listar(): void
    {
        $busqueda = trim((string) ($_GET['q'] ?? ''));
        $this->render('productos/index.php', [
            'titulo' => 'Catálogo',
            'productos' => $this->productos->listar($busqueda),
            'busqueda' => $busqueda,
        ]);
    }

    private function formulario(?int $id = null, array $data = [], array $errors = []): void
    {
        $producto = $id === null ? null : $this->productos->buscarPorId($id);
        if ($id !== null && $producto === null) {
            flash('error', 'No encontramos el producto solicitado.');
            redirect();
        }

        if ($data === [] && $producto !== null) {
            $data = $producto;
        }

        $this->render('productos/form.php', [
            'titulo' => $id === null ? 'Registrar producto' : 'Editar producto',
            'producto' => $producto,
            'datos' => $data,
            'errores' => $errors,
            'modoEdicion' => $id !== null,
        ]);
    }

    private function crear(): void
    {
        if (!$this->esPost()) {
            redirect('nuevo');
        }
        if (!$this->csrfValido()) {
            return;
        }

        [$errors, $data] = $this->validator->validar($_POST);
        if ($errors === [] && !$this->productos->skuDisponible((string) $data['sku'])) {
            $errors['sku'] = 'Ya existe un producto con ese código. Escribe uno diferente.';
        }
        if ($errors !== []) {
            $this->formulario(null, $data, $errors);
            return;
        }

        try {
            $this->productos->crear($data);
            flash('success', 'Producto registrado correctamente.');
            redirect();
        } catch (PDOException $exception) {
            error_log((string) $exception);
            $this->formulario(null, $data, ['general' => 'No pudimos guardar el producto. Revisa la conexión con MySQL e inténtalo de nuevo.']);
        }
    }

    private function actualizar(): void
    {
        if (!$this->esPost()) {
            redirect();
        }
        if (!$this->csrfValido()) {
            return;
        }

        $id = $this->idSolicitado();
        if ($this->productos->buscarPorId($id) === null) {
            flash('error', 'No encontramos el producto que intentas actualizar.');
            redirect();
        }

        [$errors, $data] = $this->validator->validar($_POST);
        if ($errors === [] && !$this->productos->skuDisponible((string) $data['sku'], $id)) {
            $errors['sku'] = 'Ese código ya pertenece a otro producto.';
        }
        if ($errors !== []) {
            $this->formulario($id, $data, $errors);
            return;
        }

        try {
            $this->productos->actualizar($id, $data);
            flash('success', 'Cambios guardados correctamente.');
            redirect();
        } catch (PDOException $exception) {
            error_log((string) $exception);
            $this->formulario($id, $data, ['general' => 'No pudimos guardar los cambios. Revisa la conexión con MySQL e inténtalo de nuevo.']);
        }
    }

    private function eliminar(): void
    {
        if (!$this->esPost()) {
            redirect();
        }
        if (!$this->csrfValido()) {
            return;
        }

        $id = $this->idSolicitado();
        if ($this->productos->buscarPorId($id) === null) {
            flash('error', 'El producto ya no existe en el catálogo.');
            redirect();
        }

        $this->productos->eliminar($id);
        flash('success', 'Producto eliminado del catálogo.');
        redirect();
    }

    private function csrfValido(): bool
    {
        if (csrf_is_valid()) {
            return true;
        }

        http_response_code(419);
        $this->render('errores/csrf.php', ['titulo' => 'Formulario vencido']);
        return false;
    }

    private function esPost(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    private function idSolicitado(): int
    {
        $id = filter_var($_GET['id'] ?? $_POST['id'] ?? null, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);
        if ($id === false || $id === null) {
            $this->noEncontrado();
            exit;
        }
        return (int) $id;
    }

    private function noEncontrado(): void
    {
        http_response_code(404);
        $this->render('errores/404.php', ['titulo' => 'Página no encontrada']);
    }

    /** @param array<string, mixed> $data */
    private function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require dirname(__DIR__) . '/views/layout/header.php';
        require dirname(__DIR__) . '/views/' . $view;
        require dirname(__DIR__) . '/views/layout/footer.php';
    }
}
