<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

use kartik\widgets\FileInput;
use kartik\icons\Icon;

Icon::map($this, Icon::FA);  
/* @var $this yii\web\View */

$this->title = 'Koios — View File';
$api_key = \Yii::$app->params['googleMapsApiKey'];
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2 white padding-25 padding-bottom-10 serif-wide">
            <?php if ($title): ?>
                <h3 class="serif-wide gray text-center"><?= $title ?></h3>
            <?php endif ?>
                <?= $content ?>
            </div>
        </div>

    </div>
</div>

<div class="spinner white hide">
    <span class="spinner-icon">
        <?= Icon::show('spinner', ['class' => 'fa-pulse fa-2x']) ?>
    </span>
</div>

<?php
$this->registerJsFile('@web/js/map.js', [
    'position' => \yii\web\View::POS_END,
    'depends' => [\yii\web\JqueryAsset::className()]
]);
?>

<?php
$this->registerJsFile('@web/js/popover.js', [
    'position' => \yii\web\View::POS_END,
    'depends' => [\yii\web\JqueryAsset::className()]
]);
?>

<?php
$this->registerJsFile("https://maps.googleapis.com/maps/api/js?signed_in=false&key={$api_key}", [
    'position' => \yii\web\View::POS_END
]);
?>