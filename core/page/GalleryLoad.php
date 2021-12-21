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
        $instance = new UserLoad($gallery[0]['user_id']);
        $user = $instance->get();

        echo sprintf('
            <div class="container-fluid tm-container-content tm-mt-40">
                <div class="row tm-mb-40">  
                    <div class="col-xl-8 col-lg-7 col-md-6 col-sm-12">
                    </div>  
                    <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12 mb-5 mt-5">
                        <div class="tm-bg-gray tm-video-details mb-5">                  
                            <div class="mb-4 d-flex flex-wrap">
                                <div class="mr-4 mb-2">
                                    <span class="tm-text-gray-dark">Gallery name: </span><span class="tm-text-primary">%s</span>
                                </div>
                                <div class="mr-4 mb-2">
                                    <span class="tm-text-gray-dark">Created by: </span><span class="tm-text-primary">%s</span>
                                </div>
                                <div class="mr-4 mb-4">
                                    <span class="tm-text-gray-dark">Description: </span><span class="tm-text-primary">%s</span>
                                </div>
                            </div>
                            <div class="text-center">
                                <h3 class="tm-text-gray-dark mb-3">License</h3>
                                <p>Free for both personal and commercial use. No need to pay anything. No need to make any attribution.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <h2 class="tm-text-primary text-center">
                        Photos of gallery "%s"
                    </h2>
                </div>
            </div>
        ',
            $gallery[0]['slug'],
            $user[0]['username'],
            $gallery[0]['description'],
            $gallery[0]['slug']
        );

        while($this->i < count($imagesId))
        {
            $image = Application::$app->db->getSingleImageById($imagesId[$this->i]['image_id']);

            echo sprintf('
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-3 mt-2">
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
                ',
                $image[0]['slug'],
                $image[0]['slug'],
            );

            $this->i++;
        }
    }

    public function getComments($id)
    {
        $this->i = 0;
        $gallery = Application::$app->db->getSingleGallery($id);
        $user = Application::$app->user;
        $comments = Application::$app->db->getCommentsForGallery($gallery[0]['id']);

        if(count($comments) == 0)
        {
            echo '
                <div class="d-flex flex-column comment-section">
                    <div class="bg-white p-2">
                        <div class="mt-2">
                            <p class="comment-text">There is no comments</p>
                        </div>
                </div>
            ';
        }
        else
        {
            while($this->i < count($comments))
            {
                $instance = new UserLoad($comments[$this->i]['user_id']);
                $commentedUser = $instance->get();

                echo sprintf('
                    <div class="d-flex flex-column comment-section">
                        <div class="bg-white p-2">
                            <div class="d-flex flex-row user-info"><img class="rounded-circle" src="assets/img/user.png" width="40" height="40">
                                <div class="d-flex flex-column justify-content-start ml-2"><span class="d-block font-weight-bold name">%s</span><span class="date text-black-50">Shared publicly</span></div>
                            </div>
                            <div class="mt-2">
                                <p class="comment-text">%s</p>
                            </div>
                        </div>
                    </div>
                    ',
                    $commentedUser[0]['username'],
                    $comments[$this->i]['comment']
                );

                $this->i++;
            }
        }
    }

    public function createComment($comment ,$id)
    {
        if(!empty($comment))
        {
            $comment = $_POST['comment'];
            $userId = Application::$app->session->get('user');
            $gallery = Application::$app->db->getSingleGallery($id);

            Application::$app->db->createCommentForGallery($userId, $gallery[0]['id'], $comment);
        }
    }

    public function numOfPages()
    {
        $instance = Application::$app->db->getNumOfGalleries();

        $numGall = $instance[0]['num'];

        return ceil($numGall/16);
    }
}