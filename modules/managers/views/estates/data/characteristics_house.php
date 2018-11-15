<?php

    use yii\helpers\Html;

/* 
 * Характеристики по дому
 */
?>

<?php if (isset($characteristics) && $characteristics) : ?>
    <table width="100%" border="0" align="center" cellpadding="10" cellspacing="10">
        <?php foreach ($characteristics as $characteristic) : ?>
        <tr>
            <td><?= $characteristic['characteristics_name'] ?>: <?= $characteristic['characteristics_value'] ?></td>
            <td>
                <?= Html::button('<span class="glyphicon glyphicon-trash"></span>', [
                        'id' => 'delete-characteristic__link',
                        'class' => 'btn btn-link btn-sm',
                        'data-characteristic-id' => $characteristic['characteristics_id'],
                    ]) 
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>        
<?php endif; ?> 
