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

1. [Routing]() - A RESTful routing library to map paths to functions.
3. [Session]() - A session library which supports native PHP sessions and Memcached.
4. [Database]() - A simple interface to PDO's MySql driver.
5. [Cache]() - A easy caching library which supports Memcached.
6. [Config]() - An ini based configuration library that supports overloading.

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
 




