<?php 
namespace app\core\page;

use app\core\Application;

class PageUser
{
    public array $user = [];

    public function __construct($id)
    {
        $this->user = Application::$app->db->getUser($id);
    }

    public function get()
    {
        return $this->user;
    }


    public function isModerator()
    {
        if($this->user[0]['role'] == 'moderator')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function isAdmin()
    {
        if($this->user[0]['role'] == 'admin')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function isYourImage($id)
    {
        $image = Application::$app->db->getSingleImageByIdWithoutRule($id);

        if($image[0]['user_id'] == Application::$app->session->get('user'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function isYourGallery($id)
    {
        $gallery = Application::$app->db->getSingleGalleryWithoutRule($id);

        if($gallery[0]['user_id'] == Application::$app->session->get('user'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function isYourProfile($id)
    {
        $instance = new PageUser($id);
        $user = $instance->get();

        if($user[0]['id'] == Application::$app->session->get('user'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function isBanned()
    {
        if($this->user[0]['status'] == 'inactive')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function isActive()
    {
        if($this->user[0]['status'] == 'active')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function profileDetails()
    {
        $registeredUser = new PageUser(Application::$app->session->get('user'));
        $user = $registeredUser->get();

        echo sprintf('
            <div class="container-fluid tm-container-content tm-mt-60">
                <div class="row tm-mb-50">            
                    <div class="col-xl-5 col-lg-5 col-md-6 col-sm-12">
                        <div class="tm-bg-gray tm-video-details">
                            <div class="text-center mb-5">
                                <h2 class="tm-text-primary">Hello <i>%s</i></h2>
                            </div>                    
                            <div class="mb-4" id="Details">
                                <div class="mr-4 mb-2">
                                    <span class="tm-text-gray-dark">Username: </span><span class="tm-text-primary">%s</span>
                                </div>
                                <div class="mr-4 mb-2 d-flex flex-wrap">
                                    <span class="tm-text-gray-dark">Email: </span><span class="tm-text-primary ms-2">%s</span>
                                </div>
                                <div class="mr-4 mb-2 d-flex flex-wrap">
                                    <span class="tm-text-gray-dark">Status: </span><span class="tm-text-primary ms-2">%s</span>
                                </div>
                            </div>
                        </div>
                    </div> 
        ',
        $user[0]['username'],
        $user[0]['username'],
        $user[0]['email'],
        $user[0]['status']
        );
    }

    public function userProfileDetails($id)
    {
        $registeredUser = new PageUser($id);
        $user = $registeredUser->get();

        echo sprintf('
            <div class="container-fluid tm-container-content tm-mt-60">
                <div class="row tm-mb-50">  
                    <div class="col-xl-2"></div>          
                    <div class="col-xl-8 col-lg-5 col-md-6 col-sm-12">
                        <div class="tm-bg-gray tm-video-details">
                            <div class="text-center mb-5">
                                <h2 class="tm-text-primary">User: <i>%s</i></h2>
                            </div>                    
                            <div class="mb-4" id="Details">
                                <div class="mr-4 mb-2">
                                    <span class="tm-text-gray-dark">Username: </span><span class="tm-text-primary">%s</span>
                                </div>
                                <div class="mr-4 mb-2 d-flex flex-wrap">
                                    <span class="tm-text-gray-dark">Email: </span><span class="tm-text-primary ms-2">%s</span>
                                </div>
                                <div class="mr-4 mb-2 d-flex flex-wrap">
                                    <span class="tm-text-gray-dark">Status: </span><span class="tm-text-primary ms-2">%s</span>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
        ',
        $user[0]['username'],
        $user[0]['username'],
        $user[0]['email'],
        $user[0]['status']
        );
    }

    public function changeUserStatus($id, $status)
    {
        if($status == 1)
        {
            $status = 'active';
        }
        else
        {
            $status = 'inactive';
        }
        
        Application::$app->db->changeStatus($id, $status);
    }

    public function changeUserRole($id, $role)
    {
        if($role == 1)
        {
            $role = 'user';
        }
        
        if($role == 2)
        {
            $role = 'moderator';
        }

        if($role == 3)
        {
            $role = 'admin';
        }

        Application::$app->db->changeRole($id, $role);
    }
}