<?php

    use yii\helpers\Url;
/* 
 * Вид дополнительного меню на странице Профиль пользователя
 */
?>

<ul class="pager">
    <?php foreach ($items as $key => $item) : ?>
        <li>
            <a class="active" href="<?= Url::to(['profile/' . $key]) ?>">
                <?= $item ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>