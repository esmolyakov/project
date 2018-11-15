<?php

    use yii\helpers\Html;
    use app\assets\AppAsset;
    use yii\widgets\Breadcrumbs;
    use app\assets\ClientsAsset;

AppAsset::register($this);
ClientsAsset::register($this);

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
<body class="p-body">
<?php $this->beginBody() ?>
    <?php $this->beginContent('@app/modules/clients/views/layouts/header.php') ?>
    <?php $this->endContent() ?>    
        
    <div class="container mx-auto row">
    <!--<div class="container mx-auto row justify-content-center">-->
        
        <?php /*= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) */ ?>
        
        <?= $content ?>
    </div>
        
    <?php // $this->beginContent('@app/modules/clients/views/layouts/footer.php') ?>
    <?php // $this->endContent() ?>

<?php $this->endBody() ?>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    
</body>
</html>
<?php $this->endPage() ?>
