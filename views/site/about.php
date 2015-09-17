<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        This is the About page. You may modify the following file to customize its content:
    </p>

    <p>
        <h3>Images</h3>
        Background <?= Html::a(
            '„Temple of Poseidon, Sounio, Greece“', 
            'https://www.flickr.com/photos/telemax/3734186861/in/photolist-6FYFE6-ggbHnc-8Fy6Ap-6hNcEs-eXZTh6-6A8GBS-5HZ1BG-7oW45S-5TJ3NS-9Kg5x6-ccDgFm-mcFXWP-hQf4aj-2jCv9j-6A4yHX-73uytB-62Wn1z-5TH6D9-62WXjK-q6c6xu-6p9vef-7oW1Uq-6A4xNp-62XEc8-hVbN1N-8FzeoS-8FAvuq-5TCcmH-nwzafz-6sXH7w-ac8TrN-5TDwEK-9Kg2ZZ-bWow8c-65Xpk9-gofMxh-gmYPss-ptdhhX-5QZgNn-oWVgfY-pJRnHk-gibgkk-ce5RwJ-psEhEe-ac6uCu-gefFTL-cuY8rC-62X8ji-95fPDV-62UqPc'
        ) ?> by Tilemahos Efthimiadis on flickr
    </p>

</div>
