<?php

use app\core\page\PageUser;
use app\core\page\PageGallery;
use app\core\exceptions\NotFoundException;

$this->title = 'User Galleries';
$this->view = 'user_galleries';

if(!key_exists('id', $_GET) || !is_numeric($_GET['id']))
{
    throw new NotFoundException();
}

$content = new PageGallery(); 
$numOfPages = $content->numOfUserPages($_GET['id']);
$instance = new PageUser($_GET['id']);
$user = $instance->get();

?>
<div class="container-fluid tm-container-content tm-mt-60">
    <div class="row mb-2">
        <h2 class="col-6 tm-text-primary">
            Galleries by <?php echo $user[0]['username'] ?>
        </h2>
    </div>
    <hr class="underline">
    <div class="row tm-mb-90 tm-gallery">
    <?php
        $content->getUserGalleries($_GET['id']);
        $page = $content->page;
    ?>
    </div>
    <div class="row tm-mb-90">
        <?php 
            $pageNum = $page;
            if($pageNum > 1)
            {
                $pageNum = $pageNum - 1;
            }
            $pageNumPre = $page-1;
            $pageNumNext = $page+1;
        ?>
        <?php 
            if($numOfPages <= 4):
        ?>
        <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col">
            <a href="/user_galleries?id=<?php echo $user[0]['id'];?>&page=<?php echo $pageNumPre; ?>" class="btn btn-primary tm-btn-prev mb-2 <?php if($page == 1){echo 'disabled';} ?>" >Previous</a>
            <div class="tm-paging d-flex">
                <a href="/user_galleries?id=<?php echo $user[0]['id'];?>&page=1" class="btn <?php if($pageNum == $page){ echo 'active'; }?> <?php if($pageNum > $numOfPages){ echo 'disabled'; }?> tm-paging-link"><?php echo $pageNum = 1; $pageNum++; ?></a>
                <a href="/user_galleries?id=<?php echo $user[0]['id'];?>&page=2" class="btn <?php if($pageNum == $page){ echo 'active'; }?> <?php if($pageNum > $numOfPages){ echo 'disabled'; }?> tm-paging-link"><?php echo $pageNum++; ?></a>
                <a href="/user_galleries?id=<?php echo $user[0]['id'];?>&page=3" class="btn <?php if($pageNum == $page){ echo 'active'; }?> <?php if($pageNum > $numOfPages){ echo 'disabled'; }?> tm-paging-link"><?php echo $pageNum++; ?></a>
                <a href="/user_galleries?id=<?php echo $user[0]['id'];?>&page=4" class="btn <?php if($pageNum == $page){ echo 'active'; }?> <?php if($pageNum > $numOfPages){ echo 'disabled'; }?> tm-paging-link"><?php echo $pageNum++; ?></a>
            </div>
            <a href="/user_galleries?id=<?php echo $user[0]['id'];?>&page=<?php echo $pageNumNext; ?>" class="btn btn-primary tm-btn-next mb-2 <?php if($page == $numOfPages){echo 'disabled';} ?>" >Next</a>
        </div>
        <?php 
            elseif($numOfPages - $page > 2):
        ?>
        <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col">
            <a href="/user_galleries?id=<?php echo $user[0]['id'];?>&page=<?php echo $pageNumPre; ?>" class="btn btn-primary tm-btn-prev mb-2 <?php if($page == 1){echo 'disabled';} ?>" >Previous</a>
            <div class="tm-paging d-flex">
                <a href="/user_galleries?id=<?php echo $user[0]['id'];?>&page=<?php echo $pageNum; ?>" class="<?php if($pageNum == $page){ echo 'active'; }?> tm-paging-link"><?php echo $pageNum; ?></a>
                <a href="/user_galleries?id=<?php echo $user[0]['id'];?>&page=<?php echo ++$pageNum; ?>" class="<?php if($pageNum == $page){ echo 'active'; }?> tm-paging-link"><?php echo $pageNum; ?></a>
                <a href="/user_galleries?id=<?php echo $user[0]['id'];?>&page=<?php echo ++$pageNum; ?>" class="<?php if($pageNum == $page){ echo 'active'; }?> tm-paging-link"><?php echo $pageNum; ?></a>
                <a href="/user_galleries?id=<?php echo $user[0]['id'];?>&page=<?php echo ++$pageNum; ?>" class="<?php if($pageNum == $page){ echo 'active'; }?> tm-paging-link"><?php echo $pageNum; ?></a>
            </div>
            <a href="/user_galleries?id=<?php echo $user[0]['id'];?>&page=<?php echo $pageNumNext; ?>" class="btn btn-primary tm-btn-next mb-2 <?php if($page == $numOfPages){echo 'disabled';} ?>" >Next</a>
        </div>
        <?php 
            elseif($numOfPages - $page <= 2 && $numOfPages > 4): 
        ?>
        <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col">
            <a href="/user_galleries?id=<?php echo $user[0]['id'];?>&page=<?php echo $pageNumPre; ?>" class="btn btn-primary tm-btn-prev mb-2 <?php if($page == 1){echo 'disabled';} ?>" >Previous</a>
            <div class="tm-paging d-flex">
                <a href="/user_galleries?id=<?php echo $user[0]['id'];?>&page=<?php echo $pageNum = $numOfPages - 3; ?>" class="<?php if($pageNum == $page){ echo 'active'; }?> tm-paging-link"><?php echo $numOfPages - 3; ?></a>
                <a href="/user_galleries?id=<?php echo $user[0]['id'];?>&page=<?php echo $pageNum = $numOfPages - 2; ?>" class="<?php if($pageNum == $page){ echo 'active'; }?> tm-paging-link"><?php echo $numOfPages - 2; ?></a>
                <a href="/user_galleries?id=<?php echo $user[0]['id'];?>&page=<?php echo $pageNum = $numOfPages - 1; ?>" class="<?php if($pageNum == $page){ echo 'active'; }?> tm-paging-link"><?php echo $numOfPages - 1; ?></a>
                <a href="/user_galleries?id=<?php echo $user[0]['id'];?>&page=<?php echo $pageNum = $numOfPages; ?>" class="<?php if($pageNum == $page){ echo 'active'; }?> tm-paging-link"><?php echo $numOfPages; ?></a>
            </div>
            <a href="/user_galleries?id=<?php echo $user[0]['id'];?>&page=<?php echo $pageNumNext; ?>" class="btn btn-primary tm-btn-next mb-2 <?php if($page == $numOfPages){echo 'disabled';} ?>" >Next</a>
        </div>  
        <?php endif; ?>          
    </div>
</div> 