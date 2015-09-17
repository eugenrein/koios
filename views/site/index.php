<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

use kartik\widgets\FileInput;
use kartik\icons\Icon;

Icon::map($this, Icon::FA);  
/* @var $this yii\web\View */

$this->title = 'Koios';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2 white padding-25 padding-bottom-10 margin-top-200 shadow-bottom" id="upload-form">

                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
                <?= $form->field($model, 'file')
                        ->widget(FileInput::classname(), [
                            'options' => ['accept' => ['text/*', 'application/xml']],
                            'pluginOptions' => [
                                'showPreview' => false,
                                'showCaption' => true,
                                'showRemove' => false,
                                'showUpload' => false,
                                'uploadUrl' => Url::to(['/site/upload'])
                            ]
                        ])
                        ->label(false); ?>
                <?php ActiveForm::end() ?>

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
$this->registerJsFile('@web/js/fileupload.js', [
    'position' => \yii\web\View::POS_END,
    'depends' => [\yii\web\JqueryAsset::className()]
]);
?>

<?php
$settings = [
    'upload_url' => Url::to(['site/upload'], true),
    'redirect_url' => Url::to(['site/view', 'file' => '{filename}'], true)
];

$this->registerJs(
    'var settings = ' . json_encode($settings) . ';',
    \yii\web\View::POS_END
)
?>