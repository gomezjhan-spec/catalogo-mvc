# Informe: integración continua con GitHub Actions

## Objetivo

Automatizar la revisión de sintaxis PHP del catálogo cada vez que se envía un cambio a `main` o `master`, y cuando se abre o actualiza una solicitud de integración hacia esas ramas.

## Flujo configurado

El archivo `.github/workflows/ci.yml` ejecuta estos pasos en una máquina Ubuntu de GitHub:

1. Descarga el contenido del repositorio.
2. Prepara PHP 8.2.
3. Ejecuta `php -l` sobre los archivos PHP de `app/`, `config/`, `public/` y el punto de entrada raíz.
4. Marca el flujo como exitoso si todos los archivos tienen sintaxis válida; de lo contrario, lo marca como fallido y muestra el archivo con el error.

## Resultados comprobados en GitHub

- **Ejecución exitosa en `main`:** [flujo inicial completado correctamente](https://github.com/gomezjhan-spec/catalogo-mvc/actions/runs/41912782320).
- **Fallo controlado en la PR #1:** [flujo que detectó el error de sintaxis](https://github.com/gomezjhan-spec/catalogo-mvc/actions/runs/41913792750). El registro señala `public/ci-demostracion.php` y un error de sintaxis en la línea 3.
- **Corrección verificada:** [flujo exitoso después de corregir el archivo](https://github.com/gomezjhan-spec/catalogo-mvc/actions/runs/41914018328).
- **Solicitud de integración de demostración:** [PR #1](https://github.com/gomezjhan-spec/catalogo-mvc/pull/1), abierta y sin fusionar. El ejemplo permanece en una rama aparte; `main` no contiene el archivo con error.

## Análisis

**¿Qué habría ocurrido si el código con un error de sintaxis llegara al servidor de producción?**

PHP no podría interpretar correctamente el archivo afectado. La página o la función que lo carga podría fallar y mostrar un error al usuario. La revisión automática detecta el problema antes de integrar el cambio. Para impedir que se integre, además se debe configurar GitHub para exigir que el control de CI pase.

**¿Cómo se compara la validación automática con revisar archivo por archivo manualmente?**

El flujo automático repite la misma revisión en cada cambio y señala el archivo que falla. Eso reduce omisiones y no depende de que una persona recuerde revisar todos los archivos. La validación es limitada a sintaxis: no sustituye las pruebas funcionales ni verifica la conexión con MySQL.

## Conclusión

GitHub Actions ejecuta una revisión corta y repetible del código PHP. La ejecución fallida muestra que un error se detecta; la corrección seguida de una ejecución exitosa demuestra el ciclo de revisión antes de continuar con el proyecto.

La revisión de sintaxis local también pasó para los 16 archivos PHP del proyecto antes de publicarlo. Para que GitHub impida integrar cambios cuando CI falla, se debe activar la protección de `main` y exigir que el control pase.
