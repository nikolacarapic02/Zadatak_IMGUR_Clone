<?php

use app\core\Application;
use app\core\form\Form;
use app\core\page\GalleryLoad;
use app\core\page\ImageLoad;
use app\core\page\UserLoad;

$this->title = 'Profile';
$this->view ='profile';

$contentImg = new ImageLoad();
$contentGall = new GalleryLoad();
$contentUser = new UserLoad(Application::$app->session->get('user'));

?>
<div class="container-fluid tm-container-content tm-mt-30">
    <?php
        echo $contentUser->profileDetails();
    ?>
    <div class="col-xl-7 col-lg-7 col-md-6 col-sm-12 mt-3">
        <div class="col-12 d-flex tm-paging-col justify-content-between align-items-center mb-4">
            <h1 class="tm-text-primary">CRUD Images/Galleries</h1>
        </div>  
        <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col mb-4">
            <button type="button" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample1" aria-expanded="false" aria-controls="collapseExample2" id="editButton">Create <span class="fa fa-plus-circle"></span></button>
        </div> 
        <!-- Create Images/Galleries -->
        <div class="col-12 d-flex justify-content-between tm-paging-col">
            <div class="collapse mt-2 col-6" id="collapseExample1">
                <h3 class="tm-text-primary">Upload Image</h3>
                <?php $form = Form::begin('', 'post'); ?>
                    <div class="form-group">
                        <input type="text" name="slug" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Image Slug" required>
                        <small id="emailHelp" class="form-text text-muted">Add a slug to your image.</small>
                    </div>
                    <div class="form-group">
                        <input type="text" name="gallery_name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Gallery Name" required>
                        <small id="emailHelp" class="form-text text-muted">Add gallery name.</small>
                    </div>
                    <div class="form-group">
		                <input type="file" name="file" class="btn" id="exampleInputPassword1" placeholder="Password">
		            </div>
                    <div class="form-group tm-text-right">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                <?php Form::end(); ?>
            </div>
            <div class="collapse mt-2 col-6" id="collapseExample1">
                <h3 class="tm-text-primary">Create Gallery</h3>
                <?php $form = Form::begin('', 'post'); ?>
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Gallery Name" required>
                        <small id="emailHelp" class="form-text text-muted">Add a name to your gallery.</small>
                    </div>
                    <div class="form-group">
                        <input type="text" name="slug" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Gallery Slug" required>
                        <small id="emailHelp" class="form-text text-muted">Add a slug to your gallery.</small>
                    </div>
                    <div class="form-group">
                        <textarea rows="8" name="description" class="form-control rounded-0" placeholder="Description"></textarea>
                    </div>
                    <div class="form-group tm-text-right">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                <?php Form::end(); ?>
            </div>
        </div>      
        <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col mb-4">
            <button type="button" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2" id="editButton">Edit <span class="fas fa-edit"></span></button>
        </div>
        <!-- Edit Images/Galleries -->
        <div class="col-12 d-flex justify-content-between tm-paging-col">
            <div class="collapse mt-2 col-6" id="collapseExample2">
                <h3 class="tm-text-primary">Edit Image</h3>
                <?php $form = Form::begin('', 'post'); ?>
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Image Name" required>
                        <small id="emailHelp" class="form-text text-muted">Add a name to your image.</small>
                    </div>
                    <div class="form-group">
                        <input type="text" name="slug" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Image Slug" required>
                        <small id="emailHelp" class="form-text text-muted">Add a slug to your image.</small>
                    </div>
                    <div class="form-group">
                        <input type="text" name="gallery_name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Gallery Name" required>
                        <small id="emailHelp" class="form-text text-muted">Add a gallery name.</small>
                    </div>
                    <div class="form-group tm-text-right">
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                <?php Form::end(); ?>
            </div>
            <div class="collapse mt-2 col-6" id="collapseExample2">
                <h3 class="tm-text-primary">Edit Gallery</h3>
                <?php $form = Form::begin('', 'post'); ?>
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Gallery Name" required>
                        <small id="emailHelp" class="form-text text-muted">Add a name to your gallery.</small>
                    </div>
                    <div class="form-group">
                        <input type="text" name="slug" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Gallery Slug" required>
                        <small id="emailHelp" class="form-text text-muted">Add a slug to your gallery.</small>
                    </div>
                    <div class="form-group">
                        <textarea rows="8" name="description" class="form-control rounded-0" placeholder="Description"></textarea>
                    </div>
                    <div class="form-group tm-text-right">
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                <?php Form::end(); ?>
            </div>
        </div>   
        <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col mb-2">
            <button type="button" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample3" aria-expanded="false" aria-controls="collapseExample2" id="editButton">Delete <span class="fas fa-trash-alt"></span></button>
        </div>
        <!-- Delete Images/Galleries -->
        <div class="col-12 d-flex justify-content-between tm-paging-col">
            <div class="collapse mt-2 col-6" id="collapseExample3">
                <h3 class="tm-text-primary">Delete Image</h3>
                <?php $form = Form::begin('', 'post'); ?>
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Image Name" required>
                        <small id="emailHelp" class="form-text text-muted">Add a name to your image.</small>
                    </div>
                    <div class="form-group tm-text-right">
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </div>
                <?php Form::end(); ?>
            </div>
            <div class="collapse mt-2 col-6" id="collapseExample3">
                <h3 class="tm-text-primary">Delete Gallery</h3>
                <?php $form = Form::begin('', 'post'); ?>
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Gallery Name" required>
                        <small id="emailHelp" class="form-text text-muted">Add a name to your gallery.</small>
                    </div>
                    <div class="form-group tm-text-right">
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </div>
                <?php Form::end(); ?>
            </div>
        </div>     
    </div>
</div>
</div>
    <div class="row tm-mb-40 tm-gallery">
        <?php
            echo $contentImg->imagesForUser();
        ?>
    </div>
    <div class="row tm-mb-40 tm-gallery">
        <?php
            echo $contentGall->galleriesOfUser()
        ?>
    </div> 
</div>
