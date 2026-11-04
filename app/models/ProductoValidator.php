<?php
declare(strict_types=1);

final class ProductoValidator
{
    public const CATEGORIAS = ['Electrónica', 'Accesorios', 'Hogar', 'Papelería', 'Otro'];

    /** @param array<string, mixed> $input
     *  @return array{0: array<string, string>, 1: array<string, string|int>}
     */
    public function validar(array $input): array
    {
        $leer = static function (string $campo, string $porDefecto = '') use ($input): string {
            $valor = $input[$campo] ?? $porDefecto;
            return is_scalar($valor) ? trim((string) $valor) : '';
        };

        $data = [
            'sku' => strtoupper($leer('sku')),
            'nombre' => $leer('nombre'),
            'categoria' => $leer('categoria'),
            'descripcion' => $leer('descripcion'),
            'precio' => $leer('precio'),
            'stock' => $leer('stock'),
            'activo' => $leer('activo', '1'),
        ];
        $errors = [];

        if (!preg_match('/^[A-Z0-9][A-Z0-9-]{2,19}$/', $data['sku'])) {
            $errors['sku'] = 'Usa de 3 a 20 caracteres: letras, números o guiones.';
        }

        $nameLength = text_length($data['nombre']);
        if ($nameLength < 3 || $nameLength > 100) {
            $errors['nombre'] = 'Escribe un nombre de 3 a 100 caracteres.';
        }

        if (!in_array($data['categoria'], self::CATEGORIAS, true)) {
            $errors['categoria'] = 'Selecciona una categoría válida.';
        }

        if (text_length($data['descripcion']) > 500) {
            $errors['descripcion'] = 'La descripción admite máximo 500 caracteres.';
        }

        if (!preg_match('/^(?:0|[1-9]\d{0,9})(?:\.\d{1,2})?$/', $data['precio'])
            || (float) $data['precio'] <= 0
            || (float) $data['precio'] > 9999999999.99) {
            $errors['precio'] = 'Ingresa un precio mayor que cero, con máximo dos decimales.';
        }

        if (!preg_match('/^(?:0|[1-9]\d{0,5})$/', $data['stock'])) {
            $errors['stock'] = 'El inventario debe ser un número entero entre 0 y 999999.';
        }

        if (!in_array($data['activo'], ['0', '1'], true)) {
            $errors['activo'] = 'Selecciona un estado válido.';
        }

        if ($errors !== []) {
            return [$errors, $data];
        }

        $data['precio'] = number_format((float) $data['precio'], 2, '.', '');
        $data['stock'] = (int) $data['stock'];
        $data['activo'] = (int) $data['activo'];
        $data['descripcion'] = $data['descripcion'] === '' ? '' : $data['descripcion'];

        return [[], $data];
    }
}
