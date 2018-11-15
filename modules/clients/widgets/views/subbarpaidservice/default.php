<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

/* 
 * Вид дополнительного навигационного меню для страниц 
 *      Платные услуги
 *      История платных услуг
 */
?>

<?php if (Yii::$app->controller->id == 'paid-services' && Yii::$app->controller->action->id == 'index') : ?>
    <nav class="navbar services-nav navbar-dark justify-content-between navbar-expand-sm p-0 carousel-item d-block">
        <ul class="nav  mx-auto text-center justify-content-center">
            <li class="nav-item">
                <div class="col-12 text-left">
                    <div class="category-select mx-auto">
                        <span class="paid-service-dropdown">
                            <?= Html::dropDownList('category_list', -1, $category_list, [
                                    'prompt' => 'Все категории']) ?>
                        </span>
                    </div>
                </div>
            </li>
            <li class="nav-item ml-auto">
                <div class="history-btn-block">
                    <?= Html::a('История', ['paid-services/order-services'], ['class' => 'btn blue-btn white-outline']) ?>
                </div>
            </li>
            <li class="nav-item">

            </li>
        </ul>
    </nav>

<?php elseif(Yii::$app->controller->id == 'paid-services' && Yii::$app->controller->action->id == 'order-services') : ?>
    <nav class="navbar services-nav navbar-dark justify-content-between navbar-expand-sm p-0 carousel-item d-block">
        <div class="container-fluid block-searsh">
            <div class="field-searsh">
                <?php
                    $form = ActiveForm::begin([
                        'id' => 'search-form',
                    ]);
                ?>

                <?= $form->field($_search, '_input')
                        ->input('text', [
                            'placeHolder' => $_search->getAttributeLabel('_input'),
                            'id' => '_search-input'])
                        ->label(false) ?>

                <?php ActiveForm::end(); ?>

            </div>

            <div class="services-lists">
                <?= Html::a('Заказать услугу', ['paid-services/index'], ['class' => '']) ?>
            </div>
        </div>
    </nav>                
<?php endif; ?>
