<?php

use app\core\form\Form;
use app\core\Application;
use app\core\page\GalleryLoad;

$content = new GalleryLoad();

if(!empty($_POST))
{
    $content->createComment($_POST['comment'], $_GET['id']);
}

?>

<div class="row tm-mb-90 tm-gallery">
<?php 
$content->details($_GET['id']);
?>
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
                        <button class="btn btn-primary btn-sm shadow-none <?php if(Application::isGuest()){ echo "disabled"; }?>" type="submit">Post comment</button>
                    </div>
                <?php Form::end() ?>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>