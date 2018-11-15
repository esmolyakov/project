<?php

    use yii\helpers\Html;
    use app\helpers\FormatHelpers;

/* 
 * Просмотр квартир
 */
?>
<?php if (isset($flats) && $flats) : ?>
<table width="100%" border="0" align="center" cellpadding="10" cellspacing="10">
    <?php foreach ($flats as $key => $flat) : ?>
        <tr>
            <td colspan="2">
                <p class="label label-success">
                    
                <?= FormatHelpers::flatAndPorch($flat['flats_number'], $flat['flats_porch']) ?>
                <?= Html::button('<span class="glyphicon glyphicon-edit"></span>', [
                        'id' => 'edit-flat__link',
                        'class' => 'btn btn-link btn-sm',
                    ]) 
                ?>
                
                <?= Html::checkbox($flat_status, $flat['status'], [
                        'id' => 'check_status__flat',
                        'data-flat' => $flat['flats_id'],
                    ]) 
                ?>
                
                </p>
            </td>
        </tr>
        <tr>
            <td width="10%">
                <?= FormatHelpers::formatUserPhoto($flat['user_photo']) ?>
            </td>
            <td>
                Собственник
                <br/>
                <?= FormatHelpers::formatFullUserName($flat['clients_surname'], $flat['clients_name'], $flat['clients_second_name'], true) ?>
            </td>
        </tr>
        <?php if (isset($flats[$key]['note']) && $flats[$key]['note']) : ?>
        <tr id="note_flat__tr-<?= $flat['flats_id'] ?>">
            <td colspan="2">
                <span class="label label-primary">Примечания</span>
                <?= Html::button('<span class="glyphicon glyphicon-plus-sign"></span>', [
                        'class' => 'btn btn-link btn-sm',
                        'id' => 'add-note',
                        'data-flat' => $flat['flats_id'],
                    ]) ?>
            </td>
        </tr>
        <?php foreach ($flats[$key]['note'] as $note) : ?>
        <tr id="note_flat__tr-<?= $flat['flats_id'] ?>">
            <td colspan="2">
                    <?= $note['notes_name'] ?>
                    <?= Html::button('<span class="glyphicon glyphicon-trash"></span>', [
                            'class' => 'btn btn-link btn-sm flat_note__delete',
                            'data-note' => $note['notes_id']]) ?>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</table>
<?php endif; ?>
