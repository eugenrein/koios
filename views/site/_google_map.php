<?php
$api_key = Yii::$app->params['googleMapsApiKey'];

$this->registerJsFile("@web/js/map.js", [
    'position' => \yii\web\View::POS_END
]);

$this->registerJsFile("https://maps.googleapis.com/maps/api/js?signed_in=false&callback=initMap&key={$api_key}", [
    'position' => \yii\web\View::POS_END
]);

?>