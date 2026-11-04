<?php
declare(strict_types=1);

final class Producto
{
    public function __construct(private readonly PDO $db)
    {
    }

    /** @return list<array<string, mixed>> */
    public function listar(string $busqueda = ''): array
    {
        $sql = 'SELECT id, sku, nombre, categoria, descripcion, precio, stock, activo, creado_en
                FROM productos';
        $parameters = [];

        if ($busqueda !== '') {
            $sql .= ' WHERE nombre LIKE :nombre OR sku LIKE :sku OR categoria LIKE :categoria';
            $term = '%' . $busqueda . '%';
            $parameters = [':nombre' => $term, ':sku' => $term, ':categoria' => $term];
        }

        $sql .= ' ORDER BY creado_en DESC, id DESC';
        $statement = $this->db->prepare($sql);
        $statement->execute($parameters);
        return $statement->fetchAll();
    }

    /** @return array<string, mixed>|null */
    public function buscarPorId(int $id): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM productos WHERE id = :id LIMIT 1');
        $statement->execute([':id' => $id]);
        $product = $statement->fetch();
        return $product === false ? null : $product;
    }

    public function skuDisponible(string $sku, ?int $excluirId = null): bool
    {
        $sql = 'SELECT 1 FROM productos WHERE sku = :sku';
        $parameters = [':sku' => $sku];

        if ($excluirId !== null) {
            $sql .= ' AND id <> :id';
            $parameters[':id'] = $excluirId;
        }

        $statement = $this->db->prepare($sql . ' LIMIT 1');
        $statement->execute($parameters);
        return $statement->fetchColumn() === false;
    }

    /** @param array<string, string|int> $data */
    public function crear(array $data): int
    {
        $statement = $this->db->prepare(
            'INSERT INTO productos (sku, nombre, categoria, descripcion, precio, stock, activo)
             VALUES (:sku, :nombre, :categoria, :descripcion, :precio, :stock, :activo)'
        );
        $statement->execute($data);
        return (int) $this->db->lastInsertId();
    }

    /** @param array<string, string|int> $data */
    public function actualizar(int $id, array $data): void
    {
        $data['id'] = $id;
        $statement = $this->db->prepare(
            'UPDATE productos
             SET sku = :sku, nombre = :nombre, categoria = :categoria,
                 descripcion = :descripcion, precio = :precio, stock = :stock, activo = :activo
             WHERE id = :id'
        );
        $statement->execute($data);
    }

    public function eliminar(int $id): void
    {
        $statement = $this->db->prepare('DELETE FROM productos WHERE id = :id');
        $statement->execute([':id' => $id]);
    }
}
