<?php

use app\core\form\Form;
use app\core\Application;
use app\core\exceptions\NotFoundException;
use app\core\page\GalleryLoad;
use app\core\page\UserLoad;

$this->title = 'Gallery Details';

$content = new GalleryLoad();

if(!key_exists('id', $_GET) || !is_numeric($_GET['id']))
{
    throw new NotFoundException();
}

if(!empty($_POST['comment']))
{
    $content->createComment($_POST['comment'], $_GET['id']);
}

if(Application::$app->session->get('user'))
{
    $user = new UserLoad(Application::$app->session->get('user'));

    if($user->isModerator())
    {
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
        
            if(!empty($_POST['hidden']))
            {
                $content->editGalleryByModerator('', $_POST['hidden'], $_GET['id']);
            }

            if(empty($_POST['nsfw']) && empty($_POST['hidden']))
            {
                $content->editGalleryByModerator('', '', $_GET['id']);
            }
        }
    }

    if($user->isAdmin())
    {
        if(!empty($_POST['name']) && !empty($_POST['slug']) && !empty($_POST['nsfw']) && !empty($_POST['hidden']) && !empty($_POST['description']))
        {
            $content->editGalleryByAdmin($_POST['name'], $_POST['slug'], $_POST['nsfw'], $_POST['hidden'], $_POST['description'], $_GET['id']);
        }
        else
        {
            if(!empty($_POST['name']))
            {
                $name = $_POST['name'];
            }
            else
            {
                $name = '';
            }
        
            if(!empty($_POST['slug']))
            {
                $slug = $_POST['slug'];
            }
            else
            {
                $slug = '';
            }
        
            if(!empty($_POST['nsfw']))
            {
                $nsfw = $_POST['nsfw'];
            }
            else
            {
                $nsfw = '';
            }
        
            if(!empty($_POST['hidden']))
            {
                $hidden = $_POST['hidden'];
            }
            else
            {
                $hidden = '';
            }
        
            if(!empty($_POST['description']))
            {
                $description = $_POST['description'];
            }
            else
            {
                $description = '';
            }
        
            $content->editGalleryByAdmin($name, $slug, $nsfw, $hidden, $description, $_GET['id']);
        }
    }
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
    <?php if(!Application::isGuest()): ?>
            <?php if($user->isModerator()): ?>
                <button type="button" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample1" aria-expanded="false" aria-controls="collapseExample1" id="editButton">Edit <span class="fas fa-edit"></span></button>
                <div class="collapse mt-2" id="collapseExample1">
                    <?php $form = Form::begin('','post')?>
                        <div class="btn-group" data-toggle="buttons">

                            <label class="btn  form-check-label">
                                <input type="checkbox"  autocomplete="off" name="nsfw" value="1" <?php if($content->isNsfw($_GET['id'])){ echo 'checked'; } ?>> <span class="checkText" >NSFW</span>
                            </label>

                            <label class="btn form-check-label">
                                <input type="checkbox"  autocomplete="off" name="hidden" value="1" <?php if($content->isHidden($_GET['id'])){ echo 'checked'; } ?>> <span class="checkText">Hidden</span>
                            </label>

                        </div>
                        <div class="form-group tm-text-right">
                            <button type="submit" class="btn btn-primary">Done</button>
                        </div>
                    <?php Form::end() ?>
                </div>
            <?php endif; ?>
            <?php if($user->isAdmin()): ?>
                <button type="button" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2" id="editButton">Edit <span class="fas fa-edit"></span></button>
                <div class="collapse mt-2" id="collapseExample2">
                    <?php $form = Form::begin('', 'post') ?>
                        <div class="form-group">
                            <input type="text" name="name" class="form-control rounded-0" placeholder="Name"/>
                        </div>
                        <div class="form-group">
                            <input type="text" name="slug" class="form-control rounded-0" placeholder="Slug"/>
                        </div>
                        <div class="form-group">
                        <div class="btn-group" data-toggle="buttons">

                            <label class="btn  form-check-label">
                                <input type="checkbox"  autocomplete="off" name="nsfw" value="1" <?php if($content->isNsfw($_GET['id'])){ echo 'checked'; } ?>> <span class="checkText">NSFW</span>
                            </label>

                            <label class="btn form-check-label">
                                <input type="checkbox"  autocomplete="off" name="hidden" value="1" <?php if($content->isHidden($_GET['id'])){ echo 'checked'; } ?>> <span class="checkText">Hidden</span>
                            </label>

                        </div>
                        </div>
                        <div class="form-group">
                            <textarea rows="8" name="description" class="form-control rounded-0" placeholder="Description"></textarea>
                        </div>

                        <div class="form-group tm-text-right">
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    <?php Form::end() ?> 
                </div>
            <?php endif; ?> 
        <?php endif; ?>
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