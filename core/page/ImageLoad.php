<?php

namespace app\core\page;

use app\core\Application;

class ImageLoad 
{
    public array $images = [];
    public int $i = 0;
    public string $page = '';

    public function __construct()
    {
        if(key_exists('page',$_GET))
        {
            if(is_numeric($_GET['page']) && $_GET['page'] != 0)
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
        
        $this->images = Application::$app->db->getImages($this->page);
        $this->i = 0;
    }
    
    public function get()
    {
        while($this->i < count($this->images)){
            $instance = new UserLoad($this->images[$this->i]['user_id']);
            $user = $instance->get();
            echo sprintf('
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
                        <a href="/other_profile">%s</a>
                    </div>
                </div>        
                ',
                $this->images[$this->i]['slug'],
                $this->images[$this->i]['slug'],
                $user[0]['username']
            );

            $this->i++;
        }
    }

    public function details($slug)
    {
        $image = Application::$app->db->getSingleImageBySlug($slug);
        $instance = new UserLoad($image[0]['user_id']);
        $user = $instance->get();

        echo sprintf('
            <div class="container-fluid tm-container-content tm-mt-60">
                <div class="row mb-4">
                    <h2 class="col-12 tm-text-primary"></h2>
                </div>
                <div class="row tm-mb-90">            
                    <div class="col-xl-8 col-lg-7 col-md-6 col-sm-12">
                        <img src="http://placekitten.com/400/400" alt="%s" class="img-fluid" id="photoDetail">
                    </div>
                    <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12">
                        <div class="tm-bg-gray tm-video-details">
                            <div class="text-center mb-5">
                                <a href="/photo_detail?name=%s" class="btn btn-primary tm-btn-big">
                                    Download
                                </a>
                            </div>                    
                            <div class="mb-4 d-flex flex-wrap">
                                <div class="mr-4 mb-2">
                                    
                                </div>
                                <div class="mr-4 mb-2">
                                    <span class="tm-text-gray-dark">Format: </span><span class="tm-text-primary">%s</span>
                                </div>
                                <div class="mr-4 mb-2">
                                    <span class="tm-text-gray-dark">File name: </span><span class="tm-text-primary">%s</span>
                                </div>
                                <div class="mr-4 mb-2">
                                    <span class="tm-text-gray-dark">Posted by: </span><span class="tm-text-primary">%s</span>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h3 class="tm-text-gray-dark mb-3">License</h3>
                                <p>Free for both personal and commercial use. No need to pay anything. No need to make any attribution.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        ',
        $image[0]['slug'],
        $image[0]['slug'],
        strtoupper(substr($image[0]['file_name'], strpos($image[0]['file_name'], ".") + 1)),
        $image[0]['slug'],
        $user[0]['username']
        );
    }
}