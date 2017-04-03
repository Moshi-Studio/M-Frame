# M-Frame
M-Frame es un marco de trabajo simple, rápido y RESTful para PHP.
Fue creado para nuestro trabajo diario simplemente porque era mucho más 
fácil de configurar y manejar que los grandes Frameworks.

## Requirements

M-Frame requires `PHP 5.3` or greater.  

## Installation

### Download the files

M-frame se encuentra en Packagist y puede instalarlo desde la terminal 
usando el siguiente comando:

```
composer require Moshi-Studio/M-Frame
```

o también puede [descargar](https://github.com/Moshi-Studio/M-Frame/archive)
los archivos directamente y extraerlos en su directorio web. 

### Configure your webserver

#### Apache

Edite su archivo `.htaccess` y coloquelo en la carpeta `/public`

``` 
RewriteEngine on
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)\?*$ index.php?__route__=/$1 [L,QSA]
```

**Nota**: Si necesita usar M-Frame en un subdirectorio agregue la linea 
 `RewriteBase /subdir/` despues de `RewriteEngine On`.

#### Nginx

#### Virtualhost

## Quick Start

## Modules

Read documentation on the individual modules available in M-Frame.

1. [M](https://github.com/Moshi-Studio/M-Frame/wiki/04-The-M-Class)
2. [App](https://github.com/Moshi-Studio/M-Frame/wiki/05-The-App-Class)
3. [Route](https://github.com/Moshi-Studio/M-Frame/wiki/07-Routing)
4. [Response](https://github.com/Moshi-Studio/M-Frame/wiki/08-Response)
5. [ErrorHandler](https://github.com/Moshi-Studio/M-Frame/wiki/09-Error-Handling)
6. [Session](https://github.com/Moshi-Studio/M-Frame/wiki/10-Session)
7. [Database](https://github.com/Moshi-Studio/M-Frame/wiki/11-Database)
8. [Cache](https://github.com/Moshi-Studio/M-Frame/wiki/12-Cache)

## Documentation

Full docs & tutorials are available on [wiki](https://github.com/Moshi-Studio/M-Frame/wiki).

## Maintainers

* [cr1st1an](https://twitter.com/cr1st1an)
* [alejandro_zv](https://twitter.com/alejandro_zv)

## Contributing

#### Code Style

All pull requests must use the [PSR-2](http://www.php-fig.org/psr/psr-2/) code style. 

## License
 
The M-Frame Framework is under the MIT License, you can view the license
[here](https://github.com/Moshi-Studio/M-Frame/master/LICENSE.txt).
 




