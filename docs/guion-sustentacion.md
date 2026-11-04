# Guion breve para sustentar el patrón MVC

## Presentación (aprox. 1–2 minutos)

> Para este taller implementé un catálogo de productos en PHP y MySQL con el patrón Modelo–Vista–Controlador. La idea principal es separar responsabilidades para que cada parte del programa tenga un trabajo claro.
>
> El **Modelo** está en `app/models/Producto.php`. Representa el acceso a los productos: obtiene el catálogo, busca un producto, revisa que el código SKU no se repita y ejecuta las operaciones para crear, actualizar y eliminar registros. Usa PDO y consultas preparadas. Las reglas para aceptar los datos están en `ProductoValidator.php`.
>
> La **Vista** está en `app/views/`. Presenta el catálogo y el formulario de registro en HTML, con estilos CSS y pequeñas mejoras con JavaScript. La vista no consulta MySQL; recibe los datos que le entrega el controlador y los muestra escapando el contenido.
>
> El **Controlador** está en `app/controllers/ProductoController.php`. Recibe la acción solicitada, lee el formulario, verifica el token de seguridad, llama al modelo, conserva los datos cuando hay errores y decide qué vista mostrar. `public/index.php` es el punto de entrada que envía la petición al controlador.
>
> Por ejemplo, cuando registro un producto, el navegador envía el formulario al punto de entrada. El controlador valida la petición y solicita al modelo guardar el producto. El modelo ejecuta el `INSERT` preparado en MySQL. Si todo sale bien, el controlador redirige al catálogo y la vista muestra el mensaje de confirmación. Así, cambiar la presentación no obliga a reescribir las consultas y la lógica de datos.

## Demostración rápida

1. Mostrar `public/index.php` y explicar que es el punto de entrada.
2. Abrir `ProductoController.php`: señalar las acciones de listar, crear, editar y eliminar.
3. Abrir `Producto.php`: mostrar el `SELECT` y el `INSERT` con parámetros preparados.
4. Abrir `ProductoValidator.php`: mostrar que las reglas se validan en el servidor además del navegador.
5. Abrir `views/productos/form.php`: mostrar el formulario, los mensajes asociados a cada campo y que los valores se conservan si hay errores.
6. En el navegador, registrar un producto de prueba, editarlo y volver al catálogo.

## Preguntas que pueden hacer

**¿Por qué no poner todo el código en un solo archivo?**

Porque mezclar SQL, validaciones y HTML dificulta encontrar errores y cambiar el sistema. Con MVC cada responsabilidad queda en un lugar identificable.

**¿Qué pasa si alguien desactiva JavaScript?**

Las reglas importantes se repiten en PHP. JavaScript mejora la interacción, pero no decide qué datos puede guardar el servidor.

**¿Cómo se evita inyectar SQL?**

Las consultas usan PDO y parámetros enlazados; los valores enviados por el usuario no se concatenan al SQL.

**¿Qué aporta separar las vistas?**

Permite ajustar la presentación y la accesibilidad sin tocar las consultas del modelo. El modelo puede alimentar otra vista con el mismo catálogo.

**¿Qué medidas de seguridad se incluyeron?**

Consultas preparadas, escape de HTML al imprimir, token CSRF en las operaciones que modifican datos y validación del lado del servidor.

## Idea para cerrar

> En resumen, MVC separa el estado y las reglas de los productos, la coordinación de las peticiones y la presentación. Esa separación hace que el catálogo sea más fácil de entender, corregir y ampliar.
