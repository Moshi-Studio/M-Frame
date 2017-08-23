<?php

mRouter()::get('/', 'DefaultController', 'home', 'page');
mRouter()::get('/subpage', 'DefaultController', 'subPage', 'page');

mRouter()::get('.*', 'DefaultController', 'notFound');
mRouter()::post('.*', 'DefaultController', 'notFound');
mRouter()::delete('.*', 'DefaultController', 'notFound');
mRouter()::put('.*', 'DefaultController', 'notFound');
