<?php
    use yii\bootstrap\Nav;
    use yii\bootstrap\NavBar;
/*
 * Шапка, меню, хлебные крошки
 */
    
?>

<style>
.navbar-global {
  background-color: #222;
}

.navbar-global .navbar-brand {
  color: white;
}

.navbar-global .navbar-user > li > a
{
  color: white;
}

.navbar-primary {
  background-color: #222;
  bottom: -20px;
  left: -30px;
  position: absolute;
  top: 47px;
  width: 200px;
  z-index: 8;
  overflow: hidden;
  -webkit-transition: all 0.1s ease-in-out;
  -moz-transition: all 0.1s ease-in-out;
  transition: all 0.1s ease-in-out;
}

.navbar-primary.collapsed {
  width: 60px;
}

.navbar-primary.collapsed .glyphicon {
  font-size: 22px;
}

.navbar-primary.collapsed .nav-label {
  display: none;
}

.btn-expand-collapse {
    position: absolute;
    display: block;
    left: 0px;
    bottom:0;
    width: 100%;
    padding: 8px 0;
    border-top:solid 1px #666;
    color: grey;
    font-size: 20px;
    text-align: center;
}

.btn-expand-collapse:hover,
.btn-expand-collapse:focus {
    background-color: #222;
    color: white;
}

.btn-expand-collapse:active {
    background-color: #111;
}

.navbar-primary-menu,
.navbar-primary-menu li {
  margin:0; padding:0;
  list-style: none;
}

.navbar-primary-menu li a {
  display: block;
  padding: 10px 18px;
  text-align: left;
  border-bottom:solid 1px #444;
  color: #ccc;
}

.navbar-primary-menu li a:hover {
  background-color: #000;
  text-decoration: none;
  color: white;
}

.navbar-primary-menu li a .glyphicon {
  margin-right: 6px;
}

.navbar-primary-menu li a:hover .glyphicon {
  color: orchid;
}

.main-content {
  margin-top: 60px;
  margin-left: 200px;
  padding: 20px;
}

.collapsed + .main-content {
  margin-left: 60px;
}
</style>

<?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-inverse navbar-global navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Главная', 'url' => ['clients/index']],
        ],
    ]);
    NavBar::end();
?>

<?php
    NavBar::begin([
        'options' => [
            'class' => 'navbar-primary',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'collapse navbar-collapse navbar-primary-menu'],
        'items' => [
            ['label' => 'Профиль +', 'url' => ['managers/index']],
            ['label' => 'Собственники +', 'url' => ['clients/index']],
            ['label' => 'Диспетчеры +', 'url' => ['employers/dispatchers']],
            ['label' => 'Специалисты +', 'url' => ['employers/specialists']],
            ['label' => 'Услуги +', 'url' => ['services/index']],
            ['label' => 'Заявки +', 'url' => ['requests/requests']],
            ['label' => 'Платные услуги +', 'url' => ['requests/paid-services']],
            ['label' => 'Настройки', 'url' => ['settings/index']],
            ['label' => 'Новости (+)', 'url' => ['news/news']],
            ['label' => 'Реклама (+)', 'url' => ['news/adverts']],
            ['label' => 'Голосование (? to ЖМ)', 'url' => ['voting/index']],
            ['label' => 'Жилищный фонд', 'url' => ['estates/index']],
            ['label' => 'Конструктор заявок', 'url' => ['']],
        ],
    ]);
    NavBar::end();
?>