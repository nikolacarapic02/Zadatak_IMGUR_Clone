<?php

use app\core\form\Form;
use app\core\Application;
use app\core\exceptions\NotFoundException;
use app\core\page\PageGallery;
use app\core\page\PageUser;

$this->title = 'Gallery Details';

$content = new PageGallery();

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
    $user = new PageUser(Application::$app->session->get('user'));

    if($user->isYourGallery($_GET['id']))
    {
        if(!empty($_POST))
        {
            if(!empty($_POST['new_name']) && !empty($_POST['slug']) && !empty($_POST['description']))
            {
                $content->editGallery($_GET['id'], $_POST['new_name'], $_POST['slug'], $_POST['description']);
            }
            else
            {
                if(!empty($_POST['new_name']))
                {
                    $newName = $_POST['new_name'];
                }
                else
                {
                    $newName = '';
                }

                if(!empty($_POST['slug']))
                {
                    $slug = $_POST['slug'];
                }
                else
                {
                    $slug = '';
                }

                if(!empty($_POST['description']))
                {
                    $description = $_POST['description'];
                }
                else
                {
                    $description = '';
                }

                $content->editGallery($_GET['id'], $newName, $slug, $description);
            }

            if(!empty($_POST['delete']))
            {
                $content->deleteGallery($_GET['id']);
            }
        }
    }

    if($user->isModerator() && !$user->isYourGallery($_GET['id']))
    {
        if(isset($_POST['submit']))
        {
            if(key_exists('nsfw',$_POST) || key_exists('hidden', $_POST))
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
                }
            }
            else
            {
                $content->editGalleryByModerator('', '', $_GET['id']);
            }
        }
    }

    if($user->isAdmin() && !$user->isYourGallery($_GET['id']))
    {
        if(isset($_POST['submit']))
        {
            if(key_exists('name', $_POST) || key_exists('slug', $_POST) || key_exists('nsfw', $_POST) || key_exists('hidden', $_POST) || key_exists('description', $_POST))
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
            else
            {
                $content->editGalleryByAdmin('', '', '', '', '', $_GET['id']);
            }
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
            <?php if($user->isYourGallery($_GET['id'])): ?>
                <button type="button" class="btn btn-primary mb-3 <?php if($user->isBanned()){ echo 'disabled'; } ?>" type="button" data-toggle="collapse" data-target="#collapseExample1" aria-expanded="false" aria-controls="collapseExample1" id="editButton">Edit <span class="fas fa-edit"></span></button>
                <div class="collapse mt-2" id="collapseExample1">
                    <?php $form = Form::begin('', 'post'); ?>
                        <div class="form-group">
                            <input type="text" name="new_name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="New Gallery Name">
                            <small id="emailHelp" class="form-text text-muted">Add a new gallery name.</small>
                        </div>
                        <div class="form-group">
                            <input type="text" name="slug" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="New Gallery Slug">
                            <small id="emailHelp" class="form-text text-muted">Add a new gallery slug.</small>
                        </div>
                        <div class="form-group">
                            <textarea rows="8" name="description" class="form-control rounded-0" placeholder="New Description"></textarea>
                            <small id="emailHelp" class="form-text text-muted">Add a new gallery description.</small>
                        </div>
                        <div class="form-group tm-text-right">
                            <button type="submit" class="btn btn-primary" name="submit" >Edit</button>
                        </div>
                    <?php Form::end(); ?>
                </div>
                <?php $form = Form::begin('', 'post'); ?>
                    <button class="btn btn-primary mb-3 <?php if($user->isBanned()){ echo 'disabled'; } ?>" type="submit" value="1" name="delete" id="editButton">Delete <span class="fas fa-trash-alt"></span></button>      
                <?php Form::end(); ?>
            <?php elseif($user->isModerator()): ?>
                <button type="button" class="btn btn-primary <?php if($user->isBanned()){ echo 'disabled'; } ?>" type="button" data-toggle="collapse" data-target="#collapseExample3" aria-expanded="false" aria-controls="collapseExample1" id="editButton">Edit <span class="fas fa-edit"></span></button>
                <div class="collapse mt-2" id="collapseExample3">
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
                            <button type="submit" class="btn btn-primary" name="submit">Done</button>
                        </div>
                    <?php Form::end() ?>
                </div>
            <?php elseif($user->isAdmin()): ?>
                <button type="button" class="btn btn-primary <?php if($user->isBanned()){ echo 'disabled'; } ?>" type="button" data-toggle="collapse" data-target="#collapseExample4" aria-expanded="false" aria-controls="collapseExample2" id="editButton">Edit <span class="fas fa-edit"></span></button>
                <div class="collapse mt-2" id="collapseExample4">
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
                            <button type="submit" class="btn btn-primary" name="submit">Done</button>
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
                        <button class="btn btn-primary btn-sm shadow-none <?php if(Application::isGuest() || $user->isBanned()){ echo "disabled"; }?>" type="submit">Post comment  <span class="fa fa-comment"></span></button>
                    </div>
                <?php Form::end() ?>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>