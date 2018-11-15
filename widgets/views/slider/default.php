<?php

    use yii\helpers\Html;

/* 
 * Слайдер
 */
?>
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators my-5 py-5">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner h-100">
        <div class="carousel-item active">
            <div class="carousel-caption d-none d-md-block slide-content">
                <h5 class="slider-h">Добро пожаловать!</h5>
                <p>
                    <?= Html::img('/images/slider/main_logo.png', ['class' => 'slider-logo', 'alt' => 'Elsa logo']) ?>
                </p>
            </div>
            <?= Html::img('/images/slider/elsa-ground.png', ['class' => 'd-block w-100', 'alt' => 'First slide']) ?>
        </div>
        <div class="carousel-item">
            <div class="carousel-caption d-none d-md-block slide-content">
                <?= Html::img('/images/slider/ELSA_product_icon.png', ['class' => 'slider-product-icon', 'alt' => 'Product icon']) ?>
                <div class="">
                    <h5 class="slider-h">ELSA</h5>
                        <p class="slide-txt-block text-center mx-auto">
                            Установите наше приложение и будьте всегда в курсе<br> 
                            последних событий, оплачивайте услуги и<br> 
                            участвуйте в голосованиях в любом месте
                        </p>
                        <div class="app-btn-group mx-auto">
                            <div class="text-center">
                                <button class="btn black-rounded-btn apple-btn-rounded">
                                    <?= Html::img('/images/slider/apstore_gliph.png', ['class' => 'store-btn-icon', 'alt' => 'Store icon']) ?>
                                    <span class="tst3"> App Store</span>
                                </button>
                                <button class="btn black-rounded-btn g-btn-rounded" type="submit">
                                    <?= Html::img('/images/slider/google_play_gliph.png', ['class' => 'store-btn-icon', 'alt' => 'Store icon']) ?>
                                    Google Play
                                </button>
                            </div>
                        </div>
                </div>
            </div>
            <?= Html::img('/images/slider/elsa-ground.png', ['class' => 'd-block w-100', 'alt' => 'Second slide']) ?>
        </div>
        <div class="carousel-item">
            <?= Html::img('/images/slider/elsa-ground.png', ['class' => 'd-block w-100', 'alt' => 'Third slide']) ?>
        </div>
    </div>
</div>