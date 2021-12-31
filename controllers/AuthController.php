<?php

namespace app\controllers;

use app\models\User;
use app\core\Request;
use app\core\Response;
use app\core\Controller;
use app\core\Application;
use app\models\LoginForm;
use app\core\middlewares\AuthMiddleware;
use app\core\page\PageUser;

class AuthController extends Controller
{
    public function __construct()
    {
        if(Application::isGuest())
        {
            $this->registerMiddleware(new AuthMiddleware(['profile', 'moderator_logging']));
        }
        else
        {
            $this->registerMiddleware(new AuthMiddleware(['moderator_logging']));
        }
    }

    public function login(Request $request, Response $response)
    {
        $loginForm = new LoginForm();
        if($request->isPost())
        {
            $loginForm->loadData($request->getBody());
            $loginForm->login();
            if($loginForm->validate() && $loginForm->login())
            {
                $response->redirect('/profile');
                return;
            }
        }

        $this->setLayout('auth');
        return $this->render('login', [
            'model' => $loginForm
        ]);
    }   

    public function register(Request $request)
    {
        $user = new User();
        if($request->isPost())
        {
            $user->loadData($request->getBody());
            if($user->validate() && $user->register())
            {
                Application::$app->session->setFlash('success', 'Thank you for registration');
                Application::$app->response->redirect('/');
                exit();
            }
            $this->setLayout('auth');
            return $this->render('register', [
                'model' => $user
            ]);
        }

        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $user
        ]);
    }

    public function logout(Request $request, Response $response)
    {
        Application::$app->logout();
        $response->redirect('/');
    }

    public function profile()
    {
        return $this->render('profile');
    }

    public function moderator_logging()
    {
        return $this->render('moderator_logging');
    }
}