<?php

use app\core\Application;
use app\core\form\Form;
use app\core\page\PageGallery;
use app\core\page\PageImage;
use app\core\page\PageUser;

$this->title = 'Profile';
$this->view ='profile';

$contentImg = new PageImage();
$contentGall = new PageGallery();
$contentUser = new PageUser(Application::$app->session->get('user'));

$registeredUser = new PageUser(Application::$app->session->get('user'));

$errorImage = false;
$errorGallery = false;

if(isset($_POST['submitGallery']))
{
    if(key_exists('slug', $_POST) && key_exists('name', $_POST) && key_exists('description', $_POST))
    {
        if(!empty($_POST['slug'] && !empty($_POST['name']) && !empty($_POST['description'])))
        {
            $user = $contentUser->get();
            $contentGall->createGallery($_POST['name'], $_POST['slug'], $_POST['description'], $user[0]['id']);
            $errorGallery = false;
        }
        else
        {
            $errorGallery = true;
        }
    }
    else
    {
        $errorGallery = true;
    }
}

if(isset($_POST['submitImage']))
{
    if(key_exists('slug', $_POST) && key_exists('file', $_FILES) && key_exists('gallery_name', $_POST))
    {
        if(!empty($_POST['slug'] && !empty($_POST['gallery_name']) && !empty($_FILES['file'])))
        {
            $user = $contentUser->get();
            $contentImg->createImage($_FILES['file'], $_POST['slug'], $_POST['gallery_name'], $user[0]['id']);
            $errorImage = false;
        }
        else
        {
            $errorImage = true;
        }
    }
    else
    {
        $errorImage = true;
    }
}

?>
<div class="container-fluid tm-container-content tm-mt-30">
    <?php
        echo $contentUser->profileDetails();
    ?>
    <div class="col-xl-7 col-lg-7 col-md-6 col-sm-12 mt-3">
        <div class="col-12 d-flex tm-paging-col justify-content-between align-items-center mb-4">
            <h1 class="tm-text-primary">Upload Image / Create Gallery</h1>
        </div>  
        <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col mb-4">
            <button type="button" class="btn btn-primary <?php if($registeredUser->isBanned()){ echo 'disabled'; } ?>" type="button" data-toggle="collapse" data-target="#collapseExample1" aria-expanded="false" aria-controls="collapseExample2" id="editButton">Create <span class="fa fa-plus-circle"></span></button>
        </div> 
        <!-- Create Images/Galleries -->
        <div class="col-12 d-flex justify-content-between tm-paging-col">
            <div class="<?php if(!$errorImage && !$errorGallery){ echo 'collapse'; } ?> mt-2 col-6" id="collapseExample1">
                <h3 class="tm-text-primary">Upload Image</h3>
                <?php $form = Form::fileFormBegin('', 'post', 'multipart/form-data'); ?>
                    <div class="form-group">
                        <input type="text" name="slug" class="form-control <?php if($errorImage){ echo 'is-invalid'; }?>" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Image Slug">
                        <?php $form->check($errorImage, 'slug') ?>
                    </div>
                    <div class="form-group">
                        <input type="text" name="gallery_name" class="form-control <?php if($errorImage){ echo 'is-invalid'; }?>" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Gallery Name">
                        <?php $form->check($errorImage, 'gallery name') ?>
                    </div>
                    <div class="form-group">
		                <input type="file" name="file" class="btn" id="exampleInputPassword1" placeholder="Password">
		            </div>
                    <div class="form-group tm-text-right">
                        <button type="submit" name="submitImage" class="btn btn-primary">Upload</button>
                    </div>
                <?php Form::end(); ?>
            </div>
            <div class="<?php if(!$errorGallery && !$errorImage){ echo 'collapse'; } ?> mt-2 col-6" id="collapseExample1">
                <h3 class="tm-text-primary">Create Gallery</h3>
                <?php $form = Form::begin('', 'post'); ?>
                    <div class="form-group">
                        <input type="text" name="name" class="form-control <?php if($errorGallery){ echo 'is-invalid'; }?>" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Gallery Name" >
                        <?php $form->check($errorGallery, 'name') ?>
                    </div>
                    <div class="form-group">
                        <input type="text" name="slug" class="form-control <?php if($errorGallery){ echo 'is-invalid'; }?>" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Gallery Slug" >
                        <?php $form->check($errorGallery, 'slug') ?>
                    </div>
                    <div class="form-group">
                        <textarea rows="8" name="description" class="form-control rounded-0 <?php if($errorGallery){ echo 'is-invalid'; }?>" placeholder="Description"></textarea>
                        <?php $form->check($errorGallery, 'description') ?>
                    </div>
                    <div class="form-group tm-text-right">
                        <button type="submit" name="submitGallery" class="btn btn-primary">Create</button>
                    </div>
                <?php Form::end(); ?>
            </div>
        </div>      
    </div>
</div>
</div>
    <div class="row tm-mb-30 tm-gallery">
        <?php
            echo $contentImg->imagesForUser(Application::$app->session->get('user'));
        ?>
    </div>
    <div class="row tm-mb-30 tm-gallery">
        <?php
            echo $contentGall->galleriesOfUser(Application::$app->session->get('user'))
        ?>
    </div> 
</div>
