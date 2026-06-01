<div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">

    <!-- Indicators/dots -->
    <div class="carousel-indicators">
        <?php
        foreach ($newses as $key => $value) {
            $active = ($key == 0) ? 'active' : '';
            echo '<button type="button" data-bs-target="#carouselExampleFade" data-bs-interval="100" data-bs-slide-to="' . $key . '" class="' . $active . '"></button>';
        }
        ?>

    </div>

    <!-- The slideshow/carousel -->
    <div class="carousel-inner">

        <?php
        foreach ($newses as $key => $value) {
            $active = ($key == 0) ? 'active' : '';
            echo '<div class="carousel-item ' . $active . '"><img src="img/' . $value['gambar'] . '" alt="Kampus2-1" class="d-block w-100">
                <div class="carousel-caption">
                <h3>' . $value['title'] . '</h3>
            </div>
            </div>
         ';
        } ?>
    </div>

    <!-- Left and right controls/icons -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"><span class="visually-hidden"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
        <span class="carousel-control-next-icon"><span class="visually-hidden"></span>
    </button>
</div>