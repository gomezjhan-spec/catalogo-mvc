# Nexo Catálogo — taller de catálogo en MVC

Aplicación académica en PHP y MySQL para consultar, registrar, editar y eliminar productos. Está organizada con Modelo–Vista–Controlador (MVC) y agrega validaciones de datos, mensajes de ayuda y medidas básicas de seguridad.

## Requisitos

- Visual Studio Code.
- PHP 8.1 o superior, con `PDO MySQL` habilitado.
- MySQL 5.7 o superior (o MariaDB equivalente).
- Para el camino más sencillo en Windows: XAMPP con Apache, PHP y MySQL.

> No encontré un taller previo de catálogo en los archivos disponibles. Por eso este proyecto es autocontenido y toma estas decisiones explícitas: el formulario registra productos; usa las categorías Electrónica, Accesorios, Hogar, Papelería y Otro; y guarda precios en pesos colombianos (COP). No requiere Composer ni paquetes externos.

## Abrir el proyecto en VS Code

1. Descomprime `catalogo-mvc.zip`.
2. En VS Code selecciona **Archivo → Abrir carpeta…** y abre la carpeta `catalogo-mvc`.
3. Si VS Code ofrece instalar la extensión recomendada para PHP, acepta para obtener resaltado y revisión de código.

## Ejecutar con XAMPP

1. Copia la carpeta `catalogo-mvc` en `C:\xampp\htdocs\`.
2. Abre el panel de XAMPP e inicia **Apache** y **MySQL**.
3. Entra a `http://localhost/phpmyadmin/`.
4. Abre **Importar**, selecciona `database/catalogo_mvc.sql` y pulsa **Importar**. El script crea la base `catalogo_mvc`, la tabla `productos` y cinco registros de ejemplo.
5. Revisa `config/database.php`. La configuración inicial está preparada para XAMPP en Windows (`127.0.0.1`, puerto `3306`, usuario `root`, contraseña vacía). Si MySQL usa otra contraseña o puerto, actualiza esos valores.
6. Abre `http://localhost/catalogo-mvc/` en el navegador. El catálogo debe mostrar los productos de ejemplo.

## Ejecutar desde la terminal integrada de VS Code

Si PHP y MySQL ya están instalados y disponibles en la terminal:

1. Importa `database/catalogo_mvc.sql` en tu servidor MySQL y configura `config/database.php`.
2. Si importas el SQL desde la terminal MySQL, fuerza la codificación UTF-8 (`--default-character-set=utf8mb4`) para que los acentos se conserven. En phpMyAdmin, importa el archivo tal como está.
3. En VS Code abre **Terminal → Nueva terminal**; confirma que la terminal esté en la carpeta raíz `catalogo-mvc`.
4. Ejecuta `php -S 127.0.0.1:8000 -t public`.
5. Mantén esa terminal abierta y visita `http://127.0.0.1:8000/`.
6. Para detener el servidor, vuelve a la terminal y presiona `Ctrl+C`.

## Funciones incluidas

- Listado adaptable a celular, búsqueda por nombre, código SKU o categoría y estados activo/inactivo.
- Registro, edición y eliminación de productos.
- Validación en el navegador y repetición de las reglas en PHP antes de guardar.
- Código SKU único, en mayúsculas, entre 3 y 20 caracteres; nombre de 3 a 100 caracteres; categoría obligatoria; precio positivo con dos decimales; inventario entero de 0 a 999.999; descripción opcional de hasta 500 caracteres.
- Mensajes de error por campo, resumen accesible, valores conservados al corregir y contador de caracteres.
- Token CSRF en las acciones que modifican el catálogo y confirmación antes de eliminar.
- Consultas PDO preparadas, escape HTML al mostrar datos y redirección después de guardar.
- Cinco productos de demostración en el SQL inicial.

## Estructura MVC

```text
catalogo-mvc/
├── app/
│   ├── controllers/ProductoController.php  # Recibe la acción y coordina el flujo
│   ├── models/Producto.php                 # Consultas PDO y operaciones con productos
│   ├── models/ProductoValidator.php        # Reglas de validación del dominio
│   └── views/                              # HTML de catálogo, formulario y mensajes
├── config/database.php                     # Conexión local a MySQL
├── database/catalogo_mvc.sql               # Esquema y productos de demostración
└── public/
    ├── index.php                           # Punto de entrada y enrutamiento
    └── assets/                             # Estilos CSS y mejoras de interacción JS
```

- **Modelo:** representa los productos, consulta MySQL con PDO y aplica las reglas del formulario.
- **Vista:** construye el HTML, muestra los datos y comunica errores; no contiene consultas SQL.
- **Controlador:** recibe `$_GET`/`$_POST`, valida el token, llama al modelo y decide qué vista mostrar o a dónde redirigir.

## Recorrido para demostrarlo

1. En el catálogo usa el buscador para localizar un producto.
2. Pulsa **Registrar producto** y prueba enviar el formulario vacío: el navegador señala los campos requeridos.
3. Prueba un SKU repetido o un precio inválido para mostrar la validación del servidor.
4. Registra un producto válido y comprueba que aparece en la tabla.
5. Edita un campo y guarda; luego elimina un producto de ejemplo para demostrar la confirmación.

## GitHub Actions

El flujo de integración continua está en `.github/workflows/ci.yml`. En GitHub abre la pestaña **Actions** para ver el resultado de cada `push` a `main` o `master`. El flujo revisa la sintaxis de PHP con PHP 8.2. La práctica también pide mostrar una ejecución exitosa y otra fallida; registra sus enlaces y el análisis en `docs/informe-github-actions.md` después de ejecutar ambas.

## Si aparece un problema

- **“No pudimos conectar con MySQL”:** confirma que MySQL esté iniciado, que importaste el SQL y que `config/database.php` tenga las credenciales correctas.
- **`could not find driver`:** habilita `pdo_mysql` en el `php.ini` que usa Apache/PHP y reinicia Apache.
- **Error 404 al abrir:** comprueba el nombre de carpeta y que la URL termine en `/catalogo-mvc/` con XAMPP.
- **Puerto ocupado:** en XAMPP cambia el puerto de MySQL y actualiza el valor `port` en la configuración.

## Alcance académico

La aplicación es una demostración local para el taller. No incluye usuarios ni inicio de sesión y no está preparada para publicar en Internet sin agregar autenticación, permisos, registro operativo y configuración segura del servidor.
