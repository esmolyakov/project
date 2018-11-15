<?php

    use yii\helpers\Html;
    
/* 
 * Получить список все лицевых счетов пользователя
 */
?>

<li>
    
    <?= Html::beginForm() ?>
    
    <?php // = Html::dropDownList('current__account_list', '00000000002', $account_list, ['class' => 'form-control', ]) ?>

    <?= Html::dropDownList('current__account_list', 
            Yii::$app->session['choosingAccount'] ? Yii::$app->session['choosingAccount'] : 10, $account_list, [
                'class' => 'form-control current__account_list',
                'style' => 'margin-top: 7px',
                //'onchange' => "document.location.href='/" . Yii::$app->request->pathInfo . "?pageCount='+this.value;"                
            ]);
    ?>

    <?= Html::endForm() ?>    
    
</li>

<?php
$this->registerJs('
    $(".current__account_list").on("change", function() {
        var idAccount = $(this).val();
        
        $.ajax({
            url: "app/current-account&account=" + idAccount,
            method: "POST",
            typeData: "json",
            error: function() {
                console.log("error ajax");            
            },

            success: function(response) {
                console.log(response.success);
            }
        });

    })
')
?>