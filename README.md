# M Frame
M Frame es un marco de trabajo simple, rápido y RESTful para PHP. Fue creado para nuestro trabajo diario simplemente porque era mucho más fácil de configurar y manejar que los grandes Frameworks.

## Requisitos

M Frame necesita `PHP 5.3` o mayor.  

## Instalación

### Descarga los archivos

M Frame se encuentra en Packagist y puedes instalarlo desde la terminal usando el siguiente comando:

```
composer create-project moshi-studio/m-frame
```

o también puedes [descargar](https://github.com/Moshi-Studio/M-Frame/archive/master.zip)
los archivos directamente y extraerlos en tu directorio web. 

### Configura tu servidor web

#### Apache

Crea y edita el archivo `.htaccess` y colocalo en la carpeta `/public`

``` 
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)\?*$ index.php?__route__=/$1 [L,QSA]
```

**Nota**: Si necesitas usar M Frame en un subdirectorio pasa por parámetro la ruta desde tu directorio web a la subcarpeta en la función `M::App()->spacePublic('/subdir/');` en `/src/Boot.php`. 

#### Nginx

#### Virtualhost

## Inicio rápido

## Documentación

Lee la documentación de los módulos disponibles en M Frame y los distintos ejemplos. 

1. [M](https://github.com/Moshi-Studio/M-Frame/wiki/04-The-M-Class)
2. [App](https://github.com/Moshi-Studio/M-Frame/wiki/05-The-App-Class)
3. [Route](https://github.com/Moshi-Studio/M-Frame/wiki/07-Routing)
4. [Response](https://github.com/Moshi-Studio/M-Frame/wiki/08-Response)
5. [ErrorHandler](https://github.com/Moshi-Studio/M-Frame/wiki/09-Error-Handling)
6. [Session](https://github.com/Moshi-Studio/M-Frame/wiki/10-Session)
7. [Database](https://github.com/Moshi-Studio/M-Frame/wiki/11-Database)
8. [Cache](https://github.com/Moshi-Studio/M-Frame/wiki/12-Cache)
9. [Ejemplos]()

## Desarrolladores

* [cr1st1an](https://twitter.com/cr1st1an)
* [alejandro_zv](https://twitter.com/alejandro_zv)

## Contribuciones

#### Code Style

Todos los pull requests deben hacerse con el estándar [PSR-2](http://www.php-fig.org/psr/psr-2/). 

## Licencia 

M Frame se encuentra bajo [MIT License](https://github.com/Moshi-Studio/M-Frame/master/LICENSE.txt).
