<?php

/* 
 * Процент по кадому варинту ответа
 */
?>

<table width="100%">
    <tr>
        <?php foreach ($results as $key => $result) : ?>
            <td>
                <b><?= $key ?></b>
                <br />
                <?= $result ?>%
            </td>
        <?php endforeach; ?>
    </tr>
</table>