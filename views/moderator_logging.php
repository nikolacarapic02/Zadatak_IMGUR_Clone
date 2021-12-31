<?php

use app\core\Application;
use app\core\page\PageUser;

$this->title = 'Moderator Logging';
$this->view = 'moderator_logging';

$content = new PageUser(Application::$app->session->get('user'));

$content->getModeratorLogging(); 

?>
