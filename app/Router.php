<?php

use Core\M;

M::Route()->get('/', 'DefaultController', 'home');
M::Route()->get('/subpage', 'DefaultController', 'subPage');

M::Route()->get('.*', 'DefaultController', 'notFound');
M::Route()->post('.*', 'DefaultController', 'notFound');
M::Route()->delete('.*', 'DefaultController', 'notFound');
M::Route()->put('.*', 'DefaultController', 'notFound');