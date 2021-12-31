<?php 
namespace app\core\page;

use app\core\Application;
use app\core\exceptions\NotFoundException;

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

        if(!empty($image))
        {
            if($image[0]['user_id'] == Application::$app->session->get('user'))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    public function isYourGallery($id)
    {
        $gallery = Application::$app->db->getSingleGalleryWithoutRule($id);

        if(!empty($gallery))
        {
            if($gallery[0]['user_id'] == Application::$app->session->get('user'))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    public function isYourGalleryName($name)
    {
        $gallery = Application::$app->db->getGalleryByName($name);

        if(!empty($gallery))
        {
            if($gallery[0]['user_id'] == Application::$app->session->get('user'))
            {
                return true;
            }
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

        if(empty($user))
        {
            throw new NotFoundException();
        }

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

        if(empty($user))
        {
            throw new NotFoundException();
        }

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

        if(empty($user))
        {
            throw new NotFoundException();
        }

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

    public function getModeratorLogging()
    {
        $content = Application::$app->db->getModeratorLogging();
        $i = 0;

        if(empty($content))
        {

        }
        else
        {
            echo sprintf('
                <div class="container-fluid">
                    <div class="container-table100 table-responsive"> 
                        <div class="wrap-table100">
                            <div class="table100 m-b-110">
                                <div class="table100-head">
                                    <table>
                                        <thead>
                                            <tr class="row100 head">
                                                <th class="cell100 column1">Moderator ID</th>
                                                <th class="cell100 column2">Image ID</th>
                                                <th class="cell100 column3">Gallery ID</th>
                                                <th class="cell100 column4">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
            
                                <div class="table100-body js-pscroll">
                                    <table>
                                        <tbody>
                '
            );

            while($i < count($content))
            {
                echo sprintf('
                <tr class="row100 body">
                    <td class="cell100 column1">%s</td>
                    <td class="cell100 column2">%s</td>
                    <td class="cell100 column3">%s</td>
                    <td class="cell100 column4">%s</td>
                </tr>
                ',
                $content[$i]['moderator_id'],
                empty($content[$i]['image_id']) ? 'empty' : $content[$i]['image_id'],
                empty($content[$i]['gallery_id']) ? 'empty' : $content[$i]['gallery_id'],
                $content[$i]['action']
                );
            
                $i++;
            }

            echo sprintf('
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ');
        }
    }
}