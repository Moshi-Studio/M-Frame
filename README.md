# M Frame

[![Latest Stable Version](https://poser.pugx.org/moshi-studio/m-frame/v/stable)](https://packagist.org/packages/moshi-studio/m-frame)
[![Total Downloads](https://poser.pugx.org/moshi-studio/m-frame/downloads)](https://packagist.org/packages/moshi-studio/m-frame)
[![License](https://poser.pugx.org/moshi-studio/m-frame/license)](https://packagist.org/packages/moshi-studio/m-frame)

M Frame es un micro Framework de PHP ideal para crear aplicaciones web y APIs RESTful de manera simple y rápida.

## Requisitos

Necesita `PHP 5.3` o mayor.  

## Instalación

Es recomendable usar [Composer](https://getcomposer.org/) para realizar la instalación usando el siguiente comando:

```
composer create-project moshi-studio/m-frame
```

esto instalará M Frame y sus dependencias. 

### Configura tu servidor web

Usa alguna de las siguientes opciones para configurar tu servidor web (Apache o Nginx). También es recomendable que hagas uso de `Virtualhost` o `built-in PHP server` para tu proyecto. 

#### Apache

Crea el archivo `.htaccess` y colocalo en la carpeta `/public`. 

``` 
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)\?*$ index.php?__route__=/$1 [L,QSA]
```

**Nota**: Si instalas M Frame en un subdirectorio y no haces uso de `Virtualhost` o `built-in PHP server` necesitas pasar por parámetro la ruta desde tu directorio web al subdirectorio en la función `M::App()->spacePublic('/subdir/')` en `/src/Boot.php`. 

#### Nginx

```
server {
    server_name default_server _; 
    listen      [::]:80;
    listen      80;
    
    root /var/www/html/myproject/public;
    
    location / {
        index index.php;
        try_files $uri $uri/ /index.php?$args;
    }
    
    if (!-e $request_filename) {
        rewrite ^(.*) /index.php?__route__=$1 last;
    }
  
  location ~ \.(php)$ {
    fastcgi_pass   127.0.0.1:9000;
    fastcgi_index  index.php;
    fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include        fastcgi_params;
  }
}
```

#### Virtualhost Linux 

Edita el archivo `/etc/hosts` agregando la URL para tu proyecto `127.0.0.1  myproject.localhost` y el archivo `httpd-vhosts.conf` agregando la configuración del Virtualhost:

```
<VirtualHost *:80>
    DocumentRoot /var/www/html/myproject/public
    ServerName myproject.localhost
</VirtualHost>
```

#### Built-in PHP server

Desde la terminal en la carpeta `/public` de tu proyecto ejecuta: 

```
$ php -S localhost:8000
```

y ve a la ruta [http://localhost:8000/](http://localhost:8000/).

## Inicio rápido



## Contribuciones

### Desarrolladores

* [cr1st1an](https://twitter.com/cr1st1an)
* [alejandro_zv](https://twitter.com/alejandro_zv)

### Code Style

Todos los pull requests deben hacerse con el estándar [PSR-2](http://www.php-fig.org/psr/psr-2/). 

### Licencia 

M Frame se encuentra bajo [MIT License](https://github.com/Moshi-Studio/M-Frame/master/LICENSE.txt).
