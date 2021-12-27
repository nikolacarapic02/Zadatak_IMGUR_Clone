<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;

class SiteController extends Controller
{
    public function home()
    {
        return $this->render('home');
    }

    public function photos()
    {
        return $this->render('photos');
    }

    public function photo_detail()
    {
        return $this->render('photo_detail');
    }

    public function galleries()
    {
        return $this->render('galleries');
    }

    public function gallery_detail()
    {
        return $this->render('gallery_detail');
    }

    public function about()
    {
        return $this->render('about');
    }

    public function user_photos()
    {
        return $this->render('user_photos');
    }

    public function user_galleries()
    {
        return $this->render('user_galleries');
    }

    public function other_profile()
    {
        return $this->render('other_profile');
    }
}