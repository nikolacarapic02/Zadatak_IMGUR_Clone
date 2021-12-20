<?php

namespace app\core\page;

use app\core\Application;

class GalleryLoad
{
    public array $galeries = [];
    public int $i = 0;
    public string $page = '';

    public function __construct()
    {
        if(key_exists('page',$_GET))
        {
            if(is_numeric($_GET['page']) &&  $_GET['page'] != 0)
            {
                $this->page = $_GET['page']; 
            }
            else
            {
                $this->page = 1;
            }
        } 
        else
        {
            $this->page = 1;
        }
    
        $this->galeries = Application::$app->db->getGaleries($this->page);
        $this->i = 0;
    }

    public function get()
    {
        while($this->i < count($this->galeries)){
            $instance = new UserLoad($this->galeries[$this->i]['user_id']);
            $user = $instance->get();
            echo sprintf('
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
                    <figure class="effect-ming tm-video-item">
                        <img src="assets/img/gallery.jpg" alt="Image" class="img-fluid">
                        <figcaption class="d-flex align-items-center justify-content-center">
                            <h2>Details</h2>
                            <a href="/gallery_detail?id=%s">View more</a>
                        </figcaption>                    
                    </figure>
                    <div class="d-flex justify-content-between tm-text-gray">
                        <span class="tm-text-gray-light">%s</span>
                        <a href="/other_profile">%s</a>
                    </div>
                </div>        
                ',
                $this->galeries[$this->i]['id'],
                $this->galeries[$this->i]['slug'],
                $user[0]['username']
            );

            $this->i++;
        }
    }

    public function details($id)
    {
        $this->i = 0;

        $gallery = Application::$app->db->getSingleGallery($id);
        $imagesId = Application::$app->db->getImagesFromGallery($id);

        while($this->i < count($imagesId))
        {
            $image = Application::$app->db->getSingleImageById($imagesId[$this->i]['image_id']);
            $instance = new UserLoad($gallery[0]['user_id']);
            $user = $instance->get();

            echo sprintf('
                <div class="row tm-mb-10 tm-gallery">
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
                        <figure class="effect-ming tm-video-item">
                            <img src="http://placekitten.com/400/400" alt="Image" class="img-fluid">
                            <figcaption class="d-flex align-items-center justify-content-center">
                                <h2>Details</h2>
                                <a href="/photo_detail?name=%s">View more</a>
                            </figcaption>                    
                        </figure>
                        <div class="d-flex justify-content-between tm-text-gray">
                            <span class="tm-text-gray-light">%s</span>
                        </div>
                    </div> 
                </div>        
                ',
                $image[0]['slug'],
                $image[0]['slug'],
            );

            $this->i++;
        }
    }
}