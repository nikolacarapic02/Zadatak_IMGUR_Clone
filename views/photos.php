<?php

use app\core\page\ImageLoad;

$this->title = 'Photos';
$this->view = 'photos';

$content = new ImageLoad(); 
$numOfPages = $content->numOfPages();

if(key_exists('page',$_GET))
{
    if(is_numeric($_GET['page']) && $_GET['page'] != 0)
    {
        $page = $_GET['page'];

        if($page > $numOfPages)
        {
            $page = $numOfPages;
        }
    }
    else 
    {
        $page = 1;
    }
}
else
{
    $page = 1;
}

?>
<div class="container-fluid tm-container-content tm-mt-60">
    <div class="row mb-2">
        <h2 class="col-6 tm-text-primary">
            Photos
        </h2>
        <div class="col-6 d-flex justify-content-end align-items-center">
            <form action="/photos?page=" class="tm-text-primary">
                Page <input type="text" value="<?php echo  $page; ?>" size="<?php echo  $page; ?>" class="tm-input-paging tm-text-primary" name="page"> of <?php echo $numOfPages; ?>
            </form>
        </div>
    </div>
    <hr class="underline">
    <div class="row tm-mb-90 tm-gallery">
    <?php
        $content->get();
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
            if($numOfPages - $page > 2):
        ?>
        <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col">
            <a href="http://localhost:8888/photos?page=<?php echo $pageNumPre; ?>" class="btn btn-primary tm-btn-prev mb-2 <?php if($page == 1){echo 'disabled';} ?>" id="buttonPreNext">Previous</a>
            <div class="tm-paging d-flex">
                <a href="http://localhost:8888/photos?page=<?php echo $pageNum; ?>" class="<?php if($pageNum == $page){ echo 'active'; }?> tm-paging-link"><?php echo $pageNum; ?></a>
                <a href="http://localhost:8888/photos?page=<?php echo ++$pageNum; ?>" class="<?php if($pageNum == $page){ echo 'active'; }?> tm-paging-link"><?php echo $pageNum; ?></a>
                <a href="http://localhost:8888/photos?page=<?php echo ++$pageNum; ?>" class="<?php if($pageNum == $page){ echo 'active'; }?> tm-paging-link"><?php echo $pageNum; ?></a>
                <a href="http://localhost:8888/photos?page=<?php echo ++$pageNum; ?>" class="<?php if($pageNum == $page){ echo 'active'; }?> tm-paging-link"><?php echo $pageNum; ?></a>
            </div>
            <a href="http://localhost:8888/photos?page=<?php echo $pageNumNext; ?>" class="btn btn-primary tm-btn-next mb-2 <?php if($page == $numOfPages){echo 'disabled';} ?>" id="buttonPreNext">Next</a>
        </div>
        <?php endif;?>
        <?php 
            if($numOfPages - $page <= 2): 
        ?>
        <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col">
            <a href="http://localhost:8888/photos?page=<?php echo $pageNumPre; ?>" class="btn btn-primary tm-btn-prev mb-2 <?php if($page == 1){echo 'disabled';} ?>" id="buttonPreNext">Previous</a>
            <div class="tm-paging d-flex">
                <a href="http://localhost:8888/photos?page=<?php echo $pageNum = $numOfPages - 3; ?>" class="<?php if($pageNum == $page){ echo 'active'; }?> tm-paging-link"><?php echo $numOfPages - 3; ?></a>
                <a href="http://localhost:8888/photos?page=<?php echo $pageNum = $numOfPages - 2; ?>" class="<?php if($pageNum == $page){ echo 'active'; }?> tm-paging-link"><?php echo $numOfPages - 2; ?></a>
                <a href="http://localhost:8888/photos?page=<?php echo $pageNum = $numOfPages - 1; ?>" class="<?php if($pageNum == $page){ echo 'active'; }?> tm-paging-link"><?php echo $numOfPages - 1; ?></a>
                <a href="http://localhost:8888/photos?page=<?php echo $pageNum = $numOfPages; ?>" class="<?php if($pageNum == $page){ echo 'active'; }?> tm-paging-link"><?php echo $numOfPages; ?></a>
            </div>
            <a href="http://localhost:8888/photos?page=<?php echo $pageNumNext; ?>" class="btn btn-primary tm-btn-next mb-2 <?php if($page == $numOfPages){echo 'disabled';} ?>" id="buttonPreNext">Next</a>
        </div>  
        <?php endif; ?>          
    </div>
</div> 