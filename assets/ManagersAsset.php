<?php

    namespace app\assets;
    use yii\web\AssetBundle;

/*
 * Комплек ресурсов 
 * Модуль "Собстенники"
 */
class ManagersAsset extends AssetBundle {
    
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
        
    ];
    
    public $js = [
        'js/managers/managers_js.js',
        'js/lib-rating-plugin/jquery.raty.js',
    ];
    
    public $depends = [];

}
