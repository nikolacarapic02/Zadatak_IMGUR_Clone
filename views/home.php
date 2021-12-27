<?php
use app\core\page\PageGallery;
use app\core\page\PageImage;

$this->title = 'Home';
?>

<div id="carouselExampleSlidesOnly" class="carousel slide" data-mdb-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img
        src="assets/img/carousel.jpeg"
        class="d-block w-100"
        alt="Wild Landscape"
      />
    </div>
    <div class="carousel-caption d-none d-md-block">
          <h3>Make your day better</h3>
          <p>Upload some of your favorite pictures and share them with others</p>
    </div>
</div>
<div class="container-fluid tm-container-content tm-mt-60">
    <div class="row mb-1">
        <h2 class="col-6 tm-text-primary">
            Photos
        </h2>
    </div>
    <hr class="underline">
    <div class="row tm-mb-10 tm-gallery">
    <?php
    $content = new PageImage(); 
    $content->get();
    ?>
    </div>
    <div class="row tm-mb-90">
        <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col">
            <a href="/photos" class="btn btn-primary tm-btn" id="moreButton"><span class="fas fa-plus"></span>  More</a>
        </div>            
    </div>
</div> 
<div class="container-fluid tm-container-content tm-mt-60">
    <div class="row mb-1">
        <h2 class="col-6 tm-text-primary">
            Galleries
        </h2>
    </div>
    <hr class="underline">
    <div class="row tm-mb-10 tm-gallery">
    <?php
    $content = new PageGallery(); 
    $content->get();
    ?>
    </div>
    <div class="row tm-mb-90">
        <div class="col-12 d-flex justify-content-between align-items-center tm-paging-col">
            <a href="/galleries" class="btn btn-primary tm-btn" id="moreButton"><span class="fas fa-plus"></span>  More</a>
        </div>            
    </div>
</div> 