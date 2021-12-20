<?php

use app\core\page\ImageLoad;

if(key_exists('page',$_GET))
{
    if(is_numeric($_GET['page']) && $_GET['page'] != 0)
    {
        $page = $_GET['page'];
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
    <div class="row mb-4">
        <h2 class="col-6 tm-text-primary">
            Photos
        </h2>
        <div class="col-6 d-flex justify-content-end align-items-center">
            <form action="#" class="tm-text-primary">
                Page <input type="text" value="<?php echo  $page; ?>" size="<?php echo  $page; ?>" class="tm-input-paging tm-text-primary"> of 200
            </form>
        </div>
    </div>
    <div class="row tm-mb-90 tm-gallery">
    <?php
    $content = new ImageLoad($page); 
    $content->get();
    ?>
    </div>
    <div class="row tm-mb-90">
        <?php 
            $pageNum = $page;
            $pageNumPre = $pageNum-1;
            $pageNumNext = $pageNum+1;
        ?>
        <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col">
            <a href="http://localhost:8888/photos?page=<?php echo $pageNumPre; ?>" class="btn btn-primary tm-btn-prev mb-2 <?php if($pageNum == 1){echo 'disabled';} ?>">Previous</a>
            <div class="tm-paging d-flex">
                <a href="http://localhost:8888/photos?page=<?php echo $pageNum; ?>" class="active tm-paging-link"><?php echo $pageNum; ?></a>
                <a href="http://localhost:8888/photos?page=<?php echo ++$pageNum; ?>" class="tm-paging-link"><?php echo $pageNum; ?></a>
                <a href="http://localhost:8888/photos?page=<?php echo ++$pageNum; ?>" class="tm-paging-link"><?php echo $pageNum = $pageNum; ?></a></a>
                <a href="http://localhost:8888/photos?page=<?php echo ++$pageNum; ?>" class="tm-paging-link"><?php echo $pageNum = $pageNum; ?></a></a>
            </div>
            <a href="http://localhost:8888/photos?page=<?php echo $pageNumNext; ?>" class="btn btn-primary tm-btn-next">Next Page</a>
        </div>            
    </div>
</div> 