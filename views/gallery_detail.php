<?php

use app\core\page\GalleryLoad;

?>

<div class="row mb-4">
    <h2 class="col-6 tm-text-primary">
        Photos
    </h2>
</div>

<?php 
$content = new GalleryLoad();

$content->details($_GET['id']);
?>