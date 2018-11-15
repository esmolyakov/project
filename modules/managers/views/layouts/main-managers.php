<?php

    use yii\helpers\Html;
    use app\assets\AppAsset;
    use app\assets\ManagersAsset;

AppAsset::register($this);
ManagersAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

    <div class="wrap">
        
        <?php $this->beginContent('@app/modules/managers/views/layouts/header.php') ?>
        <?php $this->endContent() ?>    
        
        
        <div class="main-content">
            <?= $content ?>
        </div>
        
    </div>

    <?php $this->beginContent('@app/modules/managers/views/layouts/footer.php') ?>
    <?php $this->endContent() ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>