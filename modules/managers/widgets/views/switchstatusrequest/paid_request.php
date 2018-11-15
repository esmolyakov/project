<?php

    use app\helpers\FormatHelpers;

/*
 * Выподающий список переключения статуса заявки
 */
?>
<div class="dropdown">
    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
        <span id="value-btn"><?= FormatHelpers::statusName($status) ?></span>
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <?php foreach ($array_status as $key => $value) : ?>
            <li class="<?= ($key == $status) ? 'disabled' : '' ?>" id="status<?= $key ?>">
                <a href="#" 
                    class="switch-paid-request switch-request-status" 
                    data-status="<?= $key ?>"
                    data-request="<?= $request ?>">
                    <?= $value ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php
/*
 * Временный костыль, блокирует функциональные кнопки заявки
 */
$this->registerJs('
    if ("' . $status . '" == 4) {
        $(".btn:not(.dropdown-toggle)").attr("disabled", true);
    }
')
?>