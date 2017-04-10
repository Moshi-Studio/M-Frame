<?php

namespace App\Controller;

class DefaultController
{
    public static function home()
    {
        $response = array(
            'kind' => 'page',
            'layout' => 'default',
            'view' => 'home',
            'title' => 'Home'
        );

        return $response;
    }

    public static function subPage()
    {
        $response = array(
            'kind' => 'page',
            'layout' => 'default',
            'view' => 'subpage',
            'title' => 'Subpage'
        );

        return $response;
    }

    public static function notFound()
    {
        $response = array(
            'kind' => 'redirect',
            'url' => '/',
            'code' => 302
        );

        return $response;
    }
}