<?php

namespace app\core\middlewares;

use app\core\Application;
use app\core\exceptions\ForbidenException;
use app\core\page\PageUser;

class AuthMiddleware extends BaseMiddleware
{
    public array $actions = [];

    public function __construct(array $actions)
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if(Application::isGuest())
        {
            if(empty($this->actions) || in_array(Application::$app->controller->actions, $this->actions))
            {
                throw new ForbidenException();
            }
        }

        if(!Application::isGuest())
        {
            $registeredUser = new PageUser(Application::$app->session->get('user'));
            if(!$registeredUser->isAdmin() && in_array('moderator_logging', $this->actions))
            {
                if(empty($this->actions) || in_array(Application::$app->controller->actions, $this->actions))
                {
                    throw new ForbidenException();
                }
            }
        }
    }
}