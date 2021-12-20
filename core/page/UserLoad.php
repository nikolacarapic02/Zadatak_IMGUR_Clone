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
}