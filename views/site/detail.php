<?php

use yii\widgets\DetailView;
use yii\helpers\Html;

?>
<h1>PAP Affiliate Manager for <?=$data['name'] ;?></h1>
<?
echo DetailView::widget([
    'model' => $data,
    'attributes' => [
        'id',
        'unique_identifier',
        'name',
        'email',
        'city',
        'state',
        'country',
        'pincode',
        'phone',

    ],
]);


echo Html::button("Add to agile",['class'=>'btn btn-primary']);