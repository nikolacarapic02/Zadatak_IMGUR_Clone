<?php

use app\core\form\Form;
use app\core\Application;
use app\core\page\GalleryLoad;
use app\core\page\UserLoad;

$this->title = 'Gallery Details';

$content = new GalleryLoad();

if(!empty($_POST['comment']))
{
    $content->createComment($_POST['comment'], $_GET['id']);
}

if(!empty($_POST['nsfw']) && !empty($_POST['hidden']))
{
    $content->editGalleryByModerator($_POST['nsfw'], $_POST['hidden'], $_GET['id']);
}
else
{
    if(!empty($_POST['nsfw']))
    {
        $content->editGalleryByModerator($_POST['nsfw'], '', $_GET['id']);
    }
    else
    {
        if(!empty($_POST['hidden']))
        {
            $content->editGalleryByModerator('', $_POST['hidden'], $_GET['id']);
        }
    }
}

if(Application::$app->session->get('user'))
{
    $user = new UserLoad(Application::$app->session->get('user'));
}

?>
<div class="container-fluid tm-container-content tm-mt-30">
    <div class="row tm-mb-90 tm-gallery">
    <?php 
    $content->details($_GET['id']);
    ?>
    </div>
</div>
<div class="container-fluid tm-container-content"> 
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <?php if(!Application::isGuest()){ echo '<button type="button" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" id="editButton">Edit <span class="fas fa-edit"></span></button>'; }?> 
        <div class="collapse mt-2" id="collapseExample">
            <?php $form = Form::begin('','post')?>
                <div class="btn-group" data-toggle="buttons">

                    <label class="btn  form-check-label">
                        <input type="checkbox"  autocomplete="off" name="nsfw" value="1" <?php if($content->isNsfw($_GET['id'])){ echo 'checked'; } ?>> NSFW
                    </label>

                    <label class="btn form-check-label">
                        <input type="checkbox"  autocomplete="off" name="hidden" value="1" <?php if($content->isHidden($_GET['id'])){ echo 'checked'; } ?>> Hidden
                    </label>

                </div>
                <div class="form-group tm-text-right">
                    <button type="submit" class="btn btn-primary">Done</button>
                </div>
            <?php Form::end() ?>
        </div>
    </div>
</div> 
<div class="container mt-5 mb-5">
    <div class="row"> 
        <h2 class="tm-text-primary text-center">
            Comments
        </h2>
    </div>
    <div class="d-flex justify-content-center row">
        <div class="col-md-8">
            <?php $content->getComments($_GET['id']);?>
            <div class="bg-light p-2">
                <?php $form = Form::begin('', 'post') ?>
                    <div class="d-flex flex-row align-items-start"><img class="rounded-circle" src="assets/img/user.png" width="40">
                        <textarea class="form-control ml-1 shadow-none textarea" name="comment"></textarea>
                    </div>
                    <div class="mt-2 text-right">
                        <button class="btn btn-primary btn-sm shadow-none <?php if(Application::isGuest()){ echo "disabled"; }?>" type="submit">Post comment  <span class="fa fa-comment"></span></button>
                    </div>
                <?php Form::end() ?>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>