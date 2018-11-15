<?php
    
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\Url;

$this->title = 'Customers | Вход';
?>

<div class="col-12 p-0 mx-0 row tst6">
    <div class="col-6 p-0 my-0 mx-auto">
        <h3 class="registration-txt-h">Быcтро и удобно</h3>
        <p class="registration-txt">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>
    </div>
    <div class="col-6 my-0">
        <?= Html::img('/images/girl_elsa.png', ['class' => 'registration-picture img-fluid mx-auto']) ?>
    </div>
    <div class=" fixed-bottom fixed-bottom-btn-group col-6 ml-auto ">
        <div class="registration-btn-group mx-auto">
            <div class="text-center">
                <?= Html::a('Зарегистрироваться', ['signup/index'], ['class' => 'btn blue-outline-btn']) ?>
                <?= Html::a('Войти', ['site/login'], ['class' => 'btn blue-btn']) ?>
            </div>
        </div> 
    </div>
</div>

<?php /*
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-5">
                <h2>Главная</h2>
                <br />
                <p>Имя пользователя: <?= Yii::$app->user->identity->user_login ?></p>
            </div>
            <div class="col-lg-7">
                <h2>Модули</h2>
                <a href="<?= Url::to(['clients/clients']) ?>" class="btn btn-default">Собственник/Арендатор</a>
                <a href="<?= Url::to(['managers/managers']) ?>" class="btn btn-default">Администратор</a>
                <a href="<?= Url::to(['managers/estates/index']) ?>" class="btn btn-default">Жилой массив</a>
                <a href="<?= Url::to(['houses/index']) ?>" style="display: none;" class="btn btn-default">Клиенты</a>
            </div>
        </div>

    </div>
</div>
*/ ?>