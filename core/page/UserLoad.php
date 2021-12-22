<?php 
namespace app\core\page;

use app\core\Application;

class UserLoad
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
}