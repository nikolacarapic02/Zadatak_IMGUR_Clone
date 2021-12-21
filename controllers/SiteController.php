<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;

class SiteController extends Controller
{
    public function home(Request $request)
    {
        return $this->render('home');
    }

    public function photos(Request $request)
    {
        return $this->render('photos');
    }

    public function photo_detail(Request $request)
    {
        return $this->render('photo_detail');
    }

    public function galleries(Request $request)
    {
        return $this->render('galleries');
    }

    public function gallery_detail(Request $request)
    {
        return $this->render('gallery_detail');
    }

    public function about(Request $request)
    {
        return $this->render('about');
    }
}