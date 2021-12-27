<?php
$this->title = 'Other Profile';

use app\core\form\Form;
use app\core\Application;
use app\core\page\PageUser;
use app\core\page\PageImage;
use app\core\page\PageGallery;
use app\core\exceptions\NotFoundException;

$this->title = 'Profile';
$this->view ='profile';

if(!key_exists('id', $_GET) || !is_numeric($_GET['id']))
{
    throw new NotFoundException();
}

$contentImg = new PageImage();
$contentGall = new PageGallery();
$contentUser = new PageUser($_GET['id']);


if(Application::$app->session->get('user'))
{
    $registeredUser = new PageUser(Application::$app->session->get('user'));

    if(key_exists('status', $_POST))
    {
        $contentUser->changeUserStatus($_GET['id'], $_POST['status']);
    }

    if(key_exists('role', $_POST))
    {
        $contentUser->changeUserRole($_GET['id'], $_POST['role']);
    }
}
else
{
    $registeredUser = false;
}

$user = new PageUser($_GET['id']);

?>
<div class="container-fluid tm-container-content tm-mt-30">
    <?php
        echo $contentUser->userProfileDetails($_GET['id']);
    ?>
    <div class="row tm-mb-50">
        <div class="col-xl-2 col-lg-7 col-md-6 col-sm-12 ">
        </div>
        <div class="col-xl-8 col-lg-7 col-md-6 col-sm-12 ">
            <?php if($registeredUser):?>
                <?php if($registeredUser->isAdmin() && !$registeredUser->isYourProfile($_GET['id'])):?>
                    <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col">
                        <button type="button" class="btn btn-primary <?php if($registeredUser->isBanned()){ echo 'disabled'; } ?>" type="button" data-toggle="collapse" data-target="#collapseExample1" aria-expanded="false" aria-controls="collapseExample1" id="editButton">Edit Status <span class="fas fa-edit"></span></button>
                    </div> 
                    <div class="col-12 d-flex justify-content-between tm-paging-col mb-3">
                        <div class="collapse mt-2 col-6" id="collapseExample1">
                            <?php $form = Form::begin('','post')?>
                                <div class="btn-group" data-toggle="buttons">

                                    <label class="btn  form-check-label">
                                        <input type="radio"  autocomplete="off" name="status" value="1" <?php if($user->isActive()){ echo 'checked'; } ?>> <span class="checkText">Active</span>
                                    </label>

                                    <label class="btn form-check-label">
                                        <input type="radio"  autocomplete="off" name="status" value="2" <?php if($user->isBanned()){ echo 'checked'; } ?>> <span class="checkText">Inactive</span>
                                    </label>

                                </div>
                                <div class="form-group tm-text-right">
                                    <button type="submit" class="btn btn-primary">Done</button>
                                </div>
                            <?php Form::end() ?>
                        </div>
                    </div>  
                    <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col">
                        <button type="button" class="btn btn-primary <?php if($registeredUser->isBanned()){ echo 'disabled'; } ?>" type="button" data-toggle="collapse" data-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample1" id="editButton">Change Role <span class="fa fa-users"></span></button>
                    </div> 
                    <div class="col-12 d-flex justify-content-between tm-paging-col">
                        <div class="collapse mt-2 col-6" id="collapseExample2">
                            <?php $form = Form::begin('','post')?>
                                <div class="btn-group" data-toggle="buttons">

                                    <label class="btn  form-check-label">
                                        <input type="radio"  autocomplete="off" name="role" value="1" <?php if(!$user->isModerator() && !$user->isAdmin()){ echo 'checked'; } ?>> <span class="checkText">User</span>
                                    </label>

                                    <label class="btn form-check-label">
                                        <input type="radio"  autocomplete="off" name="role" value="2" <?php if($user->isModerator()){ echo 'checked'; } ?>> <span class="checkText">Moderator</span>
                                    </label>

                                    <label class="btn  form-check-label">
                                        <input type="radio"  autocomplete="off" name="role" value="3" <?php if($user->isAdmin()){ echo 'checked'; } ?>> <span class="checkText">Admin</span>
                                    </label>

                                </div>
                                <div class="form-group tm-text-right">
                                    <button type="submit" class="btn btn-primary">Done</button>
                                </div>
                            <?php Form::end() ?>
                        </div>
                    </div>  
                <?php endif; ?>      
            <?php endif; ?>    
        </div>
    </div>
</div>
    <div class="row tm-mb-30 tm-gallery">
        <?php
            echo $contentImg->imagesForUser($_GET['id']);
        ?>
    </div>
    <div class="row tm-mb-30 tm-gallery">
        <?php
            echo $contentGall->galleriesOfUser($_GET['id'])
        ?>
    </div> 
</div>
