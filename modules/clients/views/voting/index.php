<?php

    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\models\Voting;
    use app\helpers\FormatHelpers;

$this->title ="Голосование"
?>

<?php if (isset($voting_list) && count($voting_list) > 0) : ?>
    <?php foreach ($voting_list as $key => $voting) : ?>

<div class="card requests-card-preview  box-shadow" data-toggle="modal" data-target=".bd-example-modal-lg">
    <div class="requests-card-preview-container">
        <?= Html::img('@web' . $voting['voting_image'], ['class' => 'card-img-top requests-card-img-top']) ?>
        <span class="badge req-img-badge">
            <?= FormatHelpers::numberOfDaysToFinishVote($voting['voting_date_start'], $voting['voting_date_end']) ?>
        </span>
    </div>
    <div class="cont-ref">
        <h5 class="requests-card-preview-h row">
            <a href="<?= Url::to(['voting/view-voting', 'voting_id' => $voting['voting_id']]) ?>">
                <?= FormatHelpers::shortTitleOrText($voting['voting_title'], 25) ?>
            </a>
        </h5>
        <div class="<?= $voting['status'] == Voting::STATUS_CLOSED ? 'requests-icon-container-green ml-auto' : 'requests-icon-container-blue ml-auto' ?>">
            <?= Html::img('/images/clients/' . ($voting['status'] == Voting::STATUS_CLOSED ? 'check.svg' : 'flag.svg')) ?>
        </div>
    </div>
    <div class="card-body m-0 p-0 requests-card-preview-body">
        <p class="card-text requests-card-text">
            <?= FormatHelpers::shortTitleOrText($voting['voting_text'], 250) ?>
        </p>
    </div>
    <div class="requests-card-footer">
        <div class="requests-card-footer-txt-block">
            <p class="requests-card-footer-txt">Проголосовало<img class="micro-circle rounded-circle requests-face-micro" src="assets/img/noname.jpg"><img class="micro-circle rounded-circle" src="assets/img/noname.jpg"><img class="micro-circle rounded-circle" src="assets/img/noname.jpg"><img class="micro-circle rounded-circle" src="assets/img/noname.jpg">
                <span class="badge badge-darkblue requests-badge">18</span>
            </p>
        </div>
        <div class="requests-card-footer-txt-block">
            <p class="requests-card-footer-txt">Участвуют<img class="micro-circle rounded-circle requests-face-micro-member" src="assets/img/noname.jpg"><img class="micro-circle rounded-circle" src="assets/img/noname.jpg"><img class="micro-circle rounded-circle" src="assets/img/noname.jpg"><img class="micro-circle rounded-circle" src="assets/img/noname.jpg">
                <span class="badge badge-darkblue requests-badge">78</span>
            </p>
        </div>
    </div>
</div>

    <?php endforeach; ?>
<?php endif; ?>

<?php /*
<div class="clients-default-index">
    
    <h1><?= $this->title ?></h1>
    
    <?php if (isset($voting_list) && count($voting_list) > 0) : ?>
        <?php foreach ($voting_list as $key => $voting) : ?>

            <div class="col-md-4">
                <a href="<?= Url::to(['voting/view-voting', 'voting_id' => $voting['voting_id']]) ?>">
                    <?= Html::img('@web' . $voting['voting_image'], ['alt' => $voting['voting_title'], 'style' => 'width:100%']) ?>
                </a>
                
                <h5>
                    <?= FormatHelpers::statusNameVoting($voting['status']) ?>
                </h5>
                
                <h4>
                    <?= Html::a($voting['voting_title'], ['view-voting', 'voting_id' => $voting['voting_id']]) ?>
                </h4>
                
                <?= FormatHelpers::formatDate($voting['voting_date_start'], false) ?>
                <br />
                <?= FormatHelpers::numberOfDaysToFinishVote($voting['voting_date_start'], $voting['voting_date_end']) ?>
                <br />
                <p><?= FormatHelpers::shortTextNews($voting['voting_text']) ?></p>
                
                <?= Html::a('Голосовать', ['voting/view-voting', 'voting_id' => $voting['voting_id']]) ?>
                    
            </div>

            <?php if (($key + 1) % 3 == 0) : ?>
                <div class="clearfix"></div>
            <?php endif; ?>

        <?php endforeach; ?>
    <?php endif; ?>
        
</div>