<?php
use Dotenv\Dotenv;
use app\core\Application;
use app\controllers\AuthController;
use app\controllers\SiteController;
use app\core\page\UserLoad;
use app\models\User;

require_once __DIR__.'/../vendor/autoload.php';
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
    'userClass' => User::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
];

$app = new Application(dirname(__DIR__), $config);

$app->router->get('/', [SiteController::class,'home']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/logout', [AuthController::class, 'logout']);
$app->router->get('/profile', [AuthController::class, 'profile']);
$app->router->post('/profile', [AuthController::class, 'profile']);
$app->router->get('/user_profile', [SiteController::class, 'other_profile']);
$app->router->post('/user_profile', [SiteController::class, 'other_profile']);
$app->router->get('/photos', [SiteController::class, 'photos']);
$app->router->get('/photo_detail', [SiteController::class, 'photo_detail']);
$app->router->post('/photo_detail', [SiteController::class, 'photo_detail']);
$app->router->get("/user_photos", [SiteController::class, 'user_photos']);
$app->router->get('/galleries', [SiteController::class, 'galleries']);
$app->router->get('/gallery_detail', [SiteController::class, 'gallery_detail']);
$app->router->post('/gallery_detail', [SiteController::class, 'gallery_detail']);
$app->router->get('/user_galleries', [SiteController::class, 'user_galleries']);
$app->router->get('/about', [SiteController::class, 'about']);

$app->run();
