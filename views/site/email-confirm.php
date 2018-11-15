<?php

/*
 * Форма восстановления пароля, для входа в систему
 */
?>
<?php if (Yii::$app->session->hasFlash('registration-done')) : ?>
    <div class="alert alert-info" role="alert">
        <strong>
            <?= Yii::$app->session->getFlash('registration-done', false); ?>
        </strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>                    
    </div>
<?php endif; ?>
    
<?php if (Yii::$app->session->hasFlash('registration-error')) : ?>
    <div class="alert alert-error" role="alert">
        <strong>
            <?= Yii::$app->session->getFlash('registration-error', false); ?>
        </strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>                
    </div>
<?php endif; ?>
