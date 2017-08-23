<?php

namespace App\Controller;

class DefaultController
{
    public static function home()
    {
        $response = array(
            'layout' => 'default',
            'view' => 'home',
            'title' => 'Home'
        );

        return $response;
    }

    public static function subPage()
    {
        $response = array(
            'layout' => 'default',
            'view' => 'subpage',
            'title' => 'Subpage'
        );

        return $response;
    }

    public static function notFound()
    {
        return mRedirect('/', 302);
    }
}