<?php

namespace app\core\page;

use app\core\Application;
use app\core\exceptions\NotFoundException;

class PageGallery
{
    public array $galleries = [];
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
    
        $this->galleries = Application::$app->db->getGaleries($this->page);
        $this->i = 0;
    }

    public function isNsfw($id)
    {
        $gallery = Application::$app->db->getSingleGalleryWithoutRule($id);

        if($gallery[0]['nsfw'] == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function isHidden($id)
    {
        $gallery = Application::$app->db->getSingleGalleryWithoutRule($id);

        if($gallery[0]['hidden'] == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get()
    {
        if(Application::$app->session->get('user'))
        {
            $registeredUser = new PageUser(Application::$app->session->get('user'));

            if($registeredUser->isModerator() || $registeredUser->isAdmin())
            {
                $this->galleries = Application::$app->db->getAllGaleries($this->page);
            }
        }

        while($this->i < count($this->galleries)){
            $instance = new PageUser($this->galleries[$this->i]['user_id']);
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
                        <a href="/user_profile?id=%s">%s</a>
                    </div>
                </div>        
                ',
                $this->galleries[$this->i]['id'],
                $this->galleries[$this->i]['name'],
                $user[0]['id'],
                $user[0]['username']
            );

            $this->i++;
        }
    }

    public function getUserGalleries($id)
    {
        $instance = new PageUser($id);
        $user = $instance->get();

        if(Application::$app->session->get('user'))
        {
            $registeredUser = new PageUser(Application::$app->session->get('user'));

            if($registeredUser->isModerator() || $registeredUser->isAdmin())
            {
                $this->galleries = Application::$app->db->getAllGalleriesForUser($user[0]['id'], $this->page);
            }
            else
            {
                $this->galleries = Application::$app->db->getGalleriesForUser($user[0]['id'], $this->page);
            }
        }
        else
        {
            $this->galleries = Application::$app->db->getGalleriesForUser($user[0]['id'], $this->page);
        }

        while($this->i < count($this->galleries)){
            echo sprintf('
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
                    <figure class="effect-ming tm-video-item">
                        <img src="/assets/img/gallery.jpg" alt="Image" class="img-fluid">
                        <figcaption class="d-flex align-items-center justify-content-center">
                            <h2>Details</h2>
                            <a href="/gallery_detail?id=%s">View more</a>
                        </figcaption>                    
                    </figure>
                    <div class="d-flex justify-content-between tm-text-gray">
                        <span class="tm-text-gray-light">%s</span>
                        <a href="/user_profile?id=%s">%s</a>
                    </div>
                </div>        
                ',
                $this->galleries[$this->i]['id'],
                $this->galleries[$this->i]['name'],
                $user[0]['id'],
                $user[0]['username']
            );

            $this->i++;
        }
    }

    public function details($id)
    {
        if(Application::$app->session->get('user'))
        {
            $registeredUser = new PageUser(Application::$app->session->get('user'));

            if($registeredUser->isModerator() || $registeredUser->isAdmin())
            {
                $gallery = Application::$app->db->getSingleGalleryWithoutRule($id);
                $imagesId = Application::$app->db->getAllImagesFromGallery($id);
            }
            else
            {
                $gallery = Application::$app->db->getSingleGallery($id);
                $imagesId = Application::$app->db->getImagesFromGallery($id);
            }
        }
        else
        {
            $gallery = Application::$app->db->getSingleGallery($id);
            $imagesId = Application::$app->db->getImagesFromGallery($id);
        }

        if(empty($gallery))
        {
            throw new NotFoundException();
        }
    
        $this->i = 0;

        $instance = new PageUser($gallery[0]['user_id']);
        $user = $instance->get();
        
        echo sprintf('
            <div class="container-fluid tm-container-content tm-mt-40">
                <div class="row tm-mb-40">  
                    <div class="col-xl-1 col-lg-1 col-md-1">
                    </div>  
                    <div class="col-xl-10 col-lg-10 col-md-10 col-sm-12 mb-5 mt-5">
                        <div class="tm-bg-gray tm-video-details mb-5">                  
                            <div class="mb-4">
                                <div class="mr-4 mb-2 d-flex flex-wrap" id="Details">
                                    <span class="tm-text-gray-dark" >Gallery name: </span><span class="tm-text-primary ms-2">%s</span>
                                </div>
                                <div class="mr-4 mb-2 d-flex flex-wrap" id="Details">
                                    <span class="tm-text-gray-dark" >Created by: </span><a href="/user_profile?id=%s" class="tm-text"><span class="ms-2">%s</span></a>
                                </div>
                                <div class="mr-4 mb-4" id="Details">
                                    <span class="tm-text-gray-dark">Description: </span><span class="tm-text-primary">%s</span>
                                </div>
                            </div>
                            <div class="text-center">
                                <h3 class="tm-text-gray-dark mb-3" style="font-size: 2rem" >License</h3>
                                <p style="font-size: 1.6rem">Free for both personal and commercial use. No need to pay anything. No need to make any attribution.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <h2 class="tm-text-primary text-center">
                        Photos of gallery "%s"
                    </h2>
                </div>
                <hr class="underline">
            </div>
        ',
            $gallery[0]['name'],
            $user[0]['id'],
            $user[0]['username'],
            $gallery[0]['description'],
            $gallery[0]['slug']
        );

        while($this->i < count($imagesId))
        {
            if(Application::$app->session->get('user'))
            {
                $registeredUser = new PageUser(Application::$app->session->get('user'));

                if($registeredUser->isModerator() || $registeredUser->isAdmin())
                {
                    $image = Application::$app->db->getSingleImageByIdWithoutRule($imagesId[$this->i]['image_id']);
                }
                else
                {
                    $image = Application::$app->db->getSingleImageById($imagesId[$this->i]['image_id']);
                }
            }
            else
            {
                $image = Application::$app->db->getSingleImageById($imagesId[$this->i]['image_id']);
            }

            if(empty($gallery))
            {
                throw new NotFoundException();
            }

            echo sprintf('
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-3 mt-2">
                    <figure class="effect-ming tm-video-item">
                        <img src="%s" alt="Image" class="img-fluid">
                        <figcaption class="d-flex align-items-center justify-content-center">
                            <h2>Details</h2>
                            <a href="/photo_detail?id=%s">View more</a>
                        </figcaption>                    
                    </figure>
                    <div class="d-flex justify-content-between tm-text-gray">
                        <span class="tm-text-gray-light">%s</span>
                    </div>
                </div>          
                ',
                $image[0]['file_name'],
                $image[0]['id'],
                $image[0]['slug'],
            );

            $this->i++;
        }
    }

    public function getComments($id)
    {
        $this->i = 0;
        if(Application::$app->session->get('user'))
        {
            $registeredUser = new PageUser(Application::$app->session->get('user'));

            if($registeredUser->isModerator() || $registeredUser->isAdmin())
            {
                $gallery = Application::$app->db->getSingleGalleryWithoutRule($id);
            }
            else
            {
                $gallery = Application::$app->db->getSingleGallery($id);
            }
        }
        else
        {
            $gallery = Application::$app->db->getSingleGallery($id);
        }

        if(empty($gallery))
        {
            throw new NotFoundException();
        }

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
                $instance = new PageUser($comments[$this->i]['user_id']);
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
                    <hr class="underline" id="underlineForm">
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
        if(Application::$app->session->get('user'))
        {
            $registeredUser = new PageUser(Application::$app->session->get('user'));

            if($registeredUser->isModerator() || $registeredUser->isAdmin())
            {
                $gallery = Application::$app->db->getSingleGalleryWithoutRule($id);
            }
            else
            {
                $gallery = Application::$app->db->getSingleGallery($id);
            }
        }
        else
        {
            $gallery = Application::$app->db->getSingleGallery($id);
        }

        if(empty($gallery))
        {
            throw new NotFoundException();
        }

        if(!empty($comment))
        {
            $comment = $_POST['comment'];
            $userId = Application::$app->session->get('user');
            $registeredUser = new PageUser($userId);

            if($registeredUser->isModerator() || $registeredUser->isAdmin())
            {
                $gallery = Application::$app->db->getSingleGalleryWithoutRule($id);
            }
            else
            {
                $gallery = Application::$app->db->getSingleGallery($id);
            }   

            Application::$app->db->createCommentForGallery($userId, $gallery[0]['id'], $comment);
        }
    }

    public function editGalleryByModerator($nsfw, $hidden , $id)
    {
        $gallery = Application::$app->db->getSingleGalleryWithoutRule($id);

        if(empty($gallery))
        {
            throw new NotFoundException();
        }

        $instance = new PageUser(Application::$app->session->get('user'));
        $user = $instance->get();

        $nsfwOld = $gallery[0]['nsfw'];
        $hiddenOld = $gallery[0]['hidden'];

        if($nsfw == '')
        {
            $nsfw = 0;
        }

        if($hidden == '')
        {
            $hidden = 0;
        }

        if($nsfw == 1 && $hidden == 1)
        {
            $action = 'hidden i nsfw';
        }
        else
        {
            if($nsfw == 1 && $nsfw != $nsfwOld)
            {
                $action = 'je nsfw';
            }

            if($hidden == 1 && $hidden != $hiddenOld)
            {
                $action = 'je hidden';
            }

            if($nsfw == 0 && $nsfw != $nsfwOld)
            {
                $action = 'vise nije nsfw';
            }

            if($hidden == 0 && $hidden != $hiddenOld)
            {
                $action = 'vise nije hidden';
            }
        }

        Application::$app->db->editGalleryByModerator($nsfw, $hidden, $id);

        $newGallery = Application::$app->db->getSingleGalleryWithoutRule($id);

        $nsfwNew = $newGallery[0]['nsfw'];
        $hiddenNew = $newGallery[0]['hidden'];

        if($nsfwOld != $nsfwNew || $hiddenOld != $hiddenNew)
        {
            Application::$app->db->moderatorGalleryLogging($user[0]['id'], $user[0]['username'], $gallery[0]['id'], $gallery[0]['slug'], $action);
        }
    }

    public function editGalleryByAdmin($name, $slug, $nsfw, $hidden, $description, $id)
    {
        $gallery = Application::$app->db->getSingleGalleryWithoutRule($id);

        if(empty($gallery))
        {
            throw new NotFoundException();
        }

        $nsfwOld = $gallery[0]['nsfw'];
        $hiddenOld = $gallery[0]['hidden'];

        if($name == '')
        {
            $name = $gallery[0]['name'];
        }

        if($description == '')
        {
            $description = $gallery[0]['description'];
        }

        if($slug == '')
        {
            $slug = $gallery[0]['slug'];
        }

        if($nsfw == '')
        {
            $nsfw = 0;

            if($nsfw != $nsfwOld)
            {
                $nsfw = 1;
            }
        }
   
        if($hidden == '')
        {
            $hidden = 0;

            if($hidden != $hiddenOld)
            {
                $hidden = 1;
            }
        }

        Application::$app->db->editGalleryByAdmin($name, $slug, $nsfw, $hidden, $description, $id);
    }

    public function numOfPages()
    {
        if(Application::$app->session->get('user'))
        {
            $registeredUser = new PageUser(Application::$app->session->get('user'));

            if($registeredUser->isModerator() || $registeredUser->isAdmin())
            {
                $instance = Application::$app->db->getNumOfAllGalleries();
            }
            else
            {
                $instance = Application::$app->db->getNumOfGalleries();
            }
        }
        else
        {
            $instance = Application::$app->db->getNumOfGalleries();
        }

        $numGall = $instance[0]['num'];

        return ceil($numGall/16);
    }

    public function numOfUserPages($id)
    {
        $instance = new PageUser($id);
        $user = $instance->get();

        if(Application::$app->session->get('user'))
        {
            $registeredUser = new PageUser(Application::$app->session->get('user'));
            
            if($registeredUser->isModerator() || $registeredUser->isAdmin())
            {
                $num = Application::$app->db->getNumOfYourAllGalleries($user[0]['id']);
            }
            else
            {
                $num = Application::$app->db->getNumOfYourGalleries($user[0]['id']);
            }
        }
        else
        {
            $num = Application::$app->db->getNumOfYourGalleries($user[0]['id']);
        }

        $numImg = $num[0]['num'];

        return ceil($numImg/8);
    }

    public function galleriesOfUser($id)
    {
        $this->i = 0;
        $instance = new PageUser($id);
        $user = $instance->get();

        if(Application::$app->session->get('user'))
        {
            $registeredUser = new PageUser(Application::$app->session->get('user'));

            if($registeredUser->isModerator() || $registeredUser->isAdmin())
            {
                $galleries = Application::$app->db->getAllGalleriesForUser($user[0]['id'], $this->page);
            }
            else
            {
                $galleries = Application::$app->db->getGalleriesForUser($user[0]['id'], $this->page);
            }
        }
        else
        {
            $galleries = Application::$app->db->getGalleriesForUser($user[0]['id'], $this->page);
        }

        echo sprintf('
            <div class="container-fluid tm-container-content tm-mt-30">
                <div class="row mb-4">
                    <h2 class="tm-text-primary ">
                        Galleries of %s
                    </h2>
                </div>
                <hr class="underline">
            </div>
            ',
            $user[0]['username']
        );

        if(empty($galleries))
        {

            echo sprintf('
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-3 mt-2">
                        <p class="comment-text">There is no galleries</p>
                    </div>     
                    <div class="row tm-mb-90">
                        <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col">
                            <a href="/" class="btn btn-primary tm-btn disabled" id="moreButtonProfile"><span class="fas fa-plus"></span>  More</a>
                        </div>            
                    </div>     
                '
            );
        }
        else
        {
            while($this->i < count($galleries))
            {
                echo sprintf('
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-3 mt-2">
                            <figure class="effect-ming tm-video-item">
                                <img src="assets/img/gallery.jpg" alt="Gallery" class="img-fluid">
                                <figcaption class="d-flex align-items-center justify-content-center">
                                    <h2>Details</h2>
                                    <a href="/gallery_detail?id=%s">View more</a>
                                </figcaption>                    
                            </figure>
                            <div class="d-flex justify-content-between tm-text-gray">
                                <span class="tm-text-gray-light">%s</span>
                            </div>
                        </div>         
                    ',
                    $galleries[$this->i]['id'],
                    $galleries[$this->i]['name'],
                );

                $this->i++;
            }

            echo sprintf('
                <div class="row tm-mb-90">
                    <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col">
                        <a href="/user_galleries?id=%s" class="btn btn-primary tm-btn" id="moreButtonProfile"><span class="fas fa-plus"></span>  More</a>
                    </div>            
                </div>  
                ',
                $user[0]['id']
            );
        }
    }

    public function createGallery($name, $slug, $description, $user_id)
    {
        Application::$app->db->createGallery($name, $slug, $description, $user_id);
    }

    public function editGallery($id, $name, $slug, $description)
    {
        $gallery = Application::$app->db->getSingleGalleryWithoutRule($id);
        
        if(!empty($gallery))
        {   
            if($name == '')
            {
                $name = $gallery[0]['name'];
            }

            if($slug == '')
            {
                $slug = $gallery[0]['slug'];
            }

            if($description == '')
            {
                $description = $gallery[0]['description'];
            }

            Application::$app->db->editGallery($name, $slug, $description, $gallery[0]['id'], Application::$app->session->get('user'));
        }
    }

    public function deleteGallery($id)
    {
        Application::$app->db->deleteGalleryImageKey($id);
        Application::$app->db->deleteGalleryCommentKey($id);
        Application::$app->db->deleteGallery($id);
    }
}