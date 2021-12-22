<?php

namespace app\core\page;

use Exception;
use app\core\Application;
use app\core\exceptions\NotFoundException;
use app\models\User;

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

    public function isNsfw($slug)
    {
        $image = $image = Application::$app->db->getSingleImageBySlugWithoutRule($slug);

        if($image[0]['nsfw'] == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function isHidden($slug)
    {
        $image = $image = Application::$app->db->getSingleImageBySlugWithoutRule($slug);

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
        if(Application::$app->session->get('user'))
        {
            $registeredUser = new UserLoad(Application::$app->session->get('user'));

            if($registeredUser->isModerator() || $registeredUser->isAdmin())
            {
                $this->images = Application::$app->db->getAllImages($this->page);
            }
        }

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
        try
        {
            if(Application::$app->session->get('user'))
            {
                $registeredUser = new UserLoad(Application::$app->session->get('user'));

                if($registeredUser->isModerator() || $registeredUser->isAdmin())
                {
                    $image = Application::$app->db->getSingleImageBySlugWithoutRule($slug);
                }
                else
                {
                    $image = Application::$app->db->getSingleImageBySlug($slug);
                }
            }
            else
            {
                $image = Application::$app->db->getSingleImageBySlug($slug);
            }
            
            if(empty($image))
            {
                throw new NotFoundException();
            }
        }
        catch(\Exception $e){
            Application::$app->response->setStatusCode($e->getCode());
            echo Application::$app->view->renderView('_error',[
                'exception' => $e
            ]);
            exit();
        }

        $instance = new UserLoad($image[0]['user_id']);
        $user = $instance->get();

        echo sprintf('
            <div class="container-fluid tm-container-content tm-mt-60">
                <div class="row tm-mb-50">            
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
                            <div class="mb-4">
                                <div class="mr-4 mb-2 d-flex flex-wrap">
                                    <span class="tm-text-gray-dark">Format: </span><span class="tm-text-primary">%s</span>
                                </div>
                                <div class="mr-4 mb-2">
                                    <span class="tm-text-gray-dark">File name: </span><span class="tm-text-primary">%s</span>
                                </div>
                                <div class="mr-4 mb-2 d-flex flex-wrap">
                                    <span class="tm-text-gray-dark">Posted by: </span><a href="/other_profile" class="tm-text">%s</a>
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
        $image[0]['slug'],
        $image[0]['slug'],
        strtoupper(substr($image[0]['file_name'], strpos($image[0]['file_name'], ".") + 1)),
        $image[0]['slug'],
        $user[0]['username']
        );
    }

    public function getComments($slug)
    {
        if(Application::$app->session->get('user'))
        {
            $registeredUser = new UserLoad(Application::$app->session->get('user'));

            if($registeredUser->isModerator() || $registeredUser->isAdmin())
            {
                $image = Application::$app->db->getSingleImageBySlugWithoutRule($slug);
            }
            else
            {
                $image = Application::$app->db->getSingleImageBySlug($slug);
            }
        }
        else
        {
            $image = Application::$app->db->getSingleImageBySlug($slug);
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
                    <hr class="underline" id="underlineForm">
                    ',
                    $commentedUser[0]['username'],
                    $comments[$this->i]['comment']
                );

                $this->i++;
            }
        }
    }

    public function createComment($comment ,$slug)
    {
        $userId = Application::$app->session->get('user');
        if(Application::$app->session->get('user'))
        {
            $registeredUser = new UserLoad(Application::$app->session->get('user'));

            if($registeredUser->isModerator() || $registeredUser->isAdmin())
            {
                $image = Application::$app->db->getSingleImageBySlugWithoutRule($slug);
            }
            else
            {
                $image = Application::$app->db->getSingleImageBySlug($slug);
            }
        }
        else
        {
            $image = Application::$app->db->getSingleImageBySlug($slug);
        }

        Application::$app->db->createCommentForImage($userId, $image[0]['id'], $comment);

    }

    public function editImageByModerator($nsfw, $hidden , $slug)
    {
        $image = Application::$app->db->getSingleImageBySlugWithoutRule($slug);
        $instance = new UserLoad(Application::$app->session->get('user'));
        $user = $instance->get();

        if($nsfw == 1 && $hidden == 1)
        {
            $action = 'hidden i nsfw';
        }
        else
        {
            if($nsfw == 1)
            {
                $action = 'nsfw';
            }
            else
            {
                $action = 'hidden';
            }
        }

        if($nsfw == '')
        {
            $nsfw = $image[0]['nsfw'];
        }
        else
        {
            if($hidden == '')
            {
                $hidden = $image[0]['hidden'];
            }
        }

        Application::$app->db->editImageByModerator($nsfw, $hidden, $slug);
        Application::$app->db->moderatorImageLogging($user[0]['id'], $user[0]['username'], $image[0]['id'], $image[0]['slug'], $action);
    }

    public function numOfPages()
    {
        if(Application::$app->session->get('user'))
        {
            $registeredUser = new UserLoad(Application::$app->session->get('user'));

            if($registeredUser->isModerator() || $registeredUser->isAdmin())
            {
                $instance = Application::$app->db->getNumOfAllImages();
            }
            else
            {
                $instance = Application::$app->db->getNumOfImages();
            }
        }
        else
        {
            $instance = Application::$app->db->getNumOfImages();
        }

        $numImg = $instance[0]['num'];

        return ceil($numImg/16);
    }
}