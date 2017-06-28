<?php

use Core\Route;

Route::get('/', 'DefaultController', 'home');
Route::get('/subpage', 'DefaultController', 'subPage');

Route::get('.*', 'DefaultController', 'notFound');
Route::post('.*', 'DefaultController', 'notFound');
Route::delete('.*', 'DefaultController', 'notFound');
Route::put('.*', 'DefaultController', 'notFound');