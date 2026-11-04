# Informe: integración continua con GitHub Actions

## Objetivo

Automatizar la revisión de sintaxis PHP del catálogo cada vez que se envía un cambio a las ramas `main` o `master`, y cuando se abre una solicitud de integración hacia ellas.

## Flujo configurado

El archivo `.github/workflows/ci.yml` ejecuta estos pasos en una máquina Ubuntu de GitHub:

1. Descarga el contenido del repositorio.
2. Prepara PHP 8.2.
3. Ejecuta `php -l` sobre los archivos PHP de `app/`, `config/`, `public/` y el punto de entrada raíz.
4. Marca el flujo como exitoso si todos los archivos tienen sintaxis válida; de lo contrario, lo marca como fallido y muestra el archivo con el error.

## Resultados en GitHub

Completa esta sección después de ejecutar el flujo en el repositorio:

- Ejecución exitosa: pega el enlace de la ejecución verde de **Actions**.
- Ejecución fallida controlada: pega el enlace de la ejecución roja de **Actions**.
- Corrección aplicada: indica el archivo corregido y pega el enlace de la ejecución verde posterior.

La comprobación local antes de subir el proyecto pasó para los 16 archivos PHP. Esto sirve como revisión previa, pero no reemplaza los enlaces de las dos ejecuciones de GitHub solicitadas por la práctica.

## Análisis

**¿Qué habría ocurrido si el código con un error de sintaxis llegara al servidor de producción?**

PHP no podría interpretar correctamente el archivo afectado. La página o la función que lo carga podría fallar y mostrar un error al usuario. La revisión automática detecta el problema antes de integrar el cambio. Para impedir que se integre, además se debe configurar GitHub para exigir que el control de CI pase.

**¿Cómo se compara la validación automática con revisar archivo por archivo manualmente?**

El flujo automático repite la misma revisión en cada cambio y señala el archivo que falla. Eso reduce omisiones y no depende de que una persona recuerde revisar todos los archivos. La validación es limitada a sintaxis: no sustituye las pruebas funcionales ni verifica la conexión con MySQL.

## Conclusión

GitHub Actions ejecuta una revisión corta y repetible del código PHP. La ejecución fallida muestra que un error se detecta; la corrección seguida de una ejecución exitosa demuestra el ciclo de revisión antes de continuar con el proyecto.
