<?php

namespace app\core\page;

use app\core\Application;
use app\core\exceptions\NotFoundException;

class PageImage 
{
    public array $images = [];
    public int $i = 0;
    public string $page = '';
    
    public function __construct()
    {
        if(key_exists('page',$_GET))
        {
            if(is_numeric($_GET['page']) && $_GET['page'] > 0)
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

        if(Application::$app->session->get('user'))
        {
            $user = new PageUser(Application::$app->session->get('user'));
            if($user->isModerator() || $user->isAdmin())
            {
                $this->images = Application::$app->db->getAllImages($this->page);
            }
            else
            {
                $this->images = Application::$app->db->getImages($this->page);
            }
        }
        else
        {
            $this->images = Application::$app->db->getImages($this->page);
        }
    
        $this->i = 0;
    }

    public function isNsfw($id)
    {
        $image = Application::$app->db->getSingleImageByIdWithoutRule($id);

        if($image[0]['nsfw'] == 1)
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
        $image = Application::$app->db->getSingleImageByIdWithoutRule($id);

        if($image[0]['hidden'] == 1)
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
        while($this->i < count($this->images)){
            $instance = new PageUser($this->images[$this->i]['user_id']);
            $user = $instance->get();
            echo sprintf('
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
                    <figure class="effect-ming tm-video-item">
                        <img src="%s" alt="Image" class="img-fluid">
                        <figcaption class="d-flex align-items-center justify-content-center">
                            <h2>Details</h2>
                            <a href="/photo_detail?id=%s">View more</a>
                        </figcaption>                    
                    </figure>
                    <div class="d-flex justify-content-between tm-text-gray">
                        <span class="tm-text-gray-light">%s</span>
                        <a href="/user_profile?id=%s">%s</a>
                    </div>
                </div>        
                ',
                $this->images[$this->i]['file_name'],
                $this->images[$this->i]['id'],
                $this->images[$this->i]['slug'],
                $user[0]['id'],
                $user[0]['username']
            );

            $this->i++;
        }
    }

    public function getUserImages($id)
    {
        $instance = new PageUser($id);
        $user = $instance->get();

        if(Application::$app->session->get('user'))
        {
            $registeredUser = new PageUser(Application::$app->session->get('user'));

            if($registeredUser->isModerator() || $registeredUser->isAdmin())
            {
                $this->images = Application::$app->db->getAllImagesForUser($user[0]['id'], $this->page);
            }
            else
            {
                $this->images = Application::$app->db->getImagesForUser($user[0]['id'], $this->page);
            }
        }
        else
        {
            $this->images = Application::$app->db->getImagesForUser($user[0]['id'], $this->page);
        }

        while($this->i < count($this->images)){
            echo sprintf('
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
                    <figure class="effect-ming tm-video-item">
                        <img src="%s" alt="Image" class="img-fluid">
                        <figcaption class="d-flex align-items-center justify-content-center">
                            <h2>Details</h2>
                            <a href="/photo_detail?id=%s">View more</a>
                        </figcaption>                    
                    </figure>
                    <div class="d-flex justify-content-between tm-text-gray">
                        <span class="tm-text-gray-light">%s</span>
                        <a href="/user_profile?id=%s">%s</a>
                    </div>
                </div>        
                ',
                $this->images[$this->i]['file_name'],
                $this->images[$this->i]['id'],
                $this->images[$this->i]['slug'],
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
                $image = Application::$app->db->getSingleImageByIdWithoutRule($id);
            }
            else
            {
                $image = Application::$app->db->getSingleImageById($id);
            }
        }
        else
        {
            $image = Application::$app->db->getSingleImageById($id);
        }
            
        if(empty($image))
        {
            throw new NotFoundException();
        }

        $instance = new PageUser($image[0]['user_id']);
        $user = $instance->get();

        echo sprintf('
            <div class="container-fluid tm-container-content tm-mt-60">
                <div class="row tm-mb-50">            
                    <div class="col-xl-8 col-lg-7 col-md-6 col-sm-12">
                        <img src="%s" alt="%s" class="img-fluid" id="photoDetail">
                    </div>
                    <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12">
                        <div class="tm-bg-gray tm-video-details">
                            <div class="text-center mb-5">
                                <a href="/photo_detail?id=%s" class="btn btn-primary tm-btn-big">
                                    <span class="fas fa-download"></span>  Download
                                </a>
                            </div>                    
                            <div class="mb-4">
                                <div class="mr-4 mb-2" id="Details">
                                    <span class="tm-text-gray-dark">File name: </span><span class="tm-text-primary">%s</span>
                                </div>
                                <div class="mr-4 mb-2 d-flex flex-wrap" id="Details">
                                    <span class="tm-text-gray-dark">Posted by: </span><a href="/user_profile?id=%s" class="tm-text"><span class="ms-2">%s</span></a>
                                </div>
                            </div>
                            <div class="mb-4 text-center">
                                <h3 class="tm-text-gray-dark mb-3">License</h3>
                                <p>Free for both personal and commercial use. No need to pay anything. No need to make any attribution.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        ',
        $image[0]['file_name'],
        $image[0]['slug'],
        $image[0]['slug'],
        $image[0]['slug'],
        $user[0]['id'],
        $user[0]['username']
        );
    }

    public function getComments($id)
    {
        if(Application::$app->session->get('user'))
        {
            $registeredUser = new PageUser(Application::$app->session->get('user'));

            if($registeredUser->isModerator() || $registeredUser->isAdmin())
            {
                $image = Application::$app->db->getSingleImageByIdWithoutRule($id);
            }
            else
            {
                $image = Application::$app->db->getSingleImageById($id);
            }
        }
        else
        {
            $image = Application::$app->db->getSingleImageById($id);
        }

        if(empty($image))
        {
            throw new NotFoundException();
        }

        $comments = Application::$app->db->getCommentsForImage($image[0]['id']);

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
        $userId = Application::$app->session->get('user');

        if(Application::$app->session->get('user'))
        {
            $registeredUser = new PageUser(Application::$app->session->get('user'));

            if($registeredUser->isModerator() || $registeredUser->isAdmin())
            {
                $image = Application::$app->db->getSingleImageByIdWithoutRule($id);
            }
            else
            {
                $image = Application::$app->db->getSingleImageById($id);
            }
        }
        else
        {
            $image = Application::$app->db->getSingleImageById($id);
        }

        if(empty($image))
        {
            throw new NotFoundException();
        }

        Application::$app->db->createCommentForImage($userId, $image[0]['id'], $comment);
    }

    public function editImageByModerator($nsfw, $hidden , $id)
    {
        $image = Application::$app->db->getSingleImageByIdWithoutRule($id);

        if(empty($image))
        {
            throw new NotFoundException();
        }

        $instance = new PageUser(Application::$app->session->get('user'));
        $user = $instance->get();

        $nsfwOld = $image[0]['nsfw'];
        $hiddenOld = $image[0]['hidden'];

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
            $action = 'je hidden i nsfw';
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

        Application::$app->db->editImageByModerator($nsfw, $hidden, $id);

        $newImage = Application::$app->db->getSingleImageByIdWithoutRule($id);

        $nsfwNew = $newImage[0]['nsfw'];
        $hiddenNew = $newImage[0]['hidden'];

        if($nsfwOld != $nsfwNew || $hiddenOld != $hiddenNew)
        {
            Application::$app->db->moderatorImageLogging($user[0]['id'], $user[0]['username'], $image[0]['id'], $image[0]['slug'], $action);
        }
    }

    public function editImageByAdmin($file_name, $slug, $nsfw, $hidden, $id)
    {
        $image = Application::$app->db->getSingleImageByIdWithoutRule($id);

        if(empty($image))
        {
            throw new NotFoundException();
        }

        if($file_name == '')
        {
            $file_name = $image[0]['file_name'];
        }

        if($slug == '')
        {
            $slug = $image[0]['slug'];
        }

        if($nsfw == '')
        {
            $nsfw = 0;
        }
   
        if($hidden == '')
        {
            $hidden = 0;
        }

        Application::$app->db->editImageByAdmin($file_name, $slug, $nsfw, $hidden, $id);
    }

    public function numOfPages()
    {
        if(Application::$app->session->get('user'))
        {
            $registeredUser = new PageUser(Application::$app->session->get('user'));

            if($registeredUser->isModerator() || $registeredUser->isAdmin())
            {
                $num = Application::$app->db->getNumOfAllImages();
            }
            else
            {
                $num = Application::$app->db->getNumOfImages();
            }
        }
        else
        {
            $num = Application::$app->db->getNumOfImages();
        }

        $numImg = $num[0]['num'];

        return ceil($numImg/16);
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
                $num = Application::$app->db->getNumOfYourAllImages($user[0]['id']);
            }
            else
            {
                $num = Application::$app->db->getNumOfYourImages($user[0]['id']);
            }
        }
        else
        {
            $num = Application::$app->db->getNumOfYourImages($user[0]['id']);
        }

        $numImg = $num[0]['num'];

        return ceil($numImg/8);
    }

    public function imagesForUser($id)
    {
        $this->i = 0;
        $instance = new PageUser($id);
        $user = $instance->get();

        if(Application::$app->session->get('user'))
        {
            $registeredUser = new PageUser(Application::$app->session->get('user'));

            if($registeredUser->isModerator() || $registeredUser->isAdmin())
            {
                $images = Application::$app->db->getAllImagesForUser($user[0]['id'], $this->page);
            }
            else
            {
                $images = Application::$app->db->getImagesForUser($user[0]['id'], $this->page);
            }
        }
        else
        {
            $images = Application::$app->db->getImagesForUser($user[0]['id'], $this->page);
        }

        echo sprintf('
            <div class="container-fluid tm-container-content tm-mt-30">
                <div class="row mb-4">
                    <h2 class="tm-text-primary ">
                        Photos of %s
                    </h2>
                </div>
                <hr class="underline">
            </div>
            ',
            $user[0]['username']
        );

        if(empty($images))
        {
            echo sprintf('
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-3 mt-2">
                        <p class="comment-text">There is no images</p>
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
            while($this->i < count($images))
            {
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
                    $images[$this->i]['file_name'],
                    $images[$this->i]['id'],
                    $images[$this->i]['slug'],
                );

                $this->i++;
            }

            echo sprintf('
                <div class="row tm-mb-90">
                    <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col">
                        <a href="/user_photos?id=%s" class="btn btn-primary tm-btn" id="moreButtonProfile"><span class="fas fa-plus"></span>  More</a>
                    </div>            
                </div>  
            ',
            $user[0]['id']
            );

        }
    }

    public function createImage($file, $slug, $gallery_name, $user_id)
    {
        $image = Application::$app->db->getSingleImageBySlugWithoutRule($slug);
        $gallery = Application::$app->db->getYourGalleryByName($gallery_name, $user_id);

        $target_dir = "uploads/";
        $target_file = $target_dir . basename($file['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        if (file_exists($target_file)) 
        {
            $uploadOk = 0;
        }

        if ($_FILES["file"]["size"] > 1000000) 
        {
            $uploadOk = 0;
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) 
        {
            $uploadOk = 0;
        }

        if($uploadOk == 1)
        {
            move_uploaded_file($file["tmp_name"], $target_file);
        }

        Application::$app->db->createImage($file['name'], $slug, $user_id, $gallery_name);

        $image = Application::$app->db->getSingleImageBySlugWithoutRule($slug);

        Application::$app->db->AddToTableImageGallery($image[0]['id'], $gallery[0]['id']);
    }

    public function editImage($id, $name, $slug)
    {
        $image = Application::$app->db->getSingleImageByIdWithoutRule($id);
        
        if(!empty($image))
        {   
            if($name == '')
            {
                $name = $image[0]['file_name'];
            }

            if($slug == '')
            {
                $slug = $image[0]['slug'];
            }

            Application::$app->db->editImage($name, $slug, $image[0]['id'], Application::$app->session->get('user'));
        }
    }

    public function deleteImage($id)
    {
        Application::$app->db->deleteImageGalleryKey($id);
        Application::$app->db->deleteImageCommentKey($id);
        Application::$app->db->deleteImage($id);
    }

}