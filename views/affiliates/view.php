<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Affiliates */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Affiliates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="affiliates-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'external_id',
            'firstname',
            'lastname',
            'username',
            'refid',
            'rstatus',
            'street',
            'city',
            'state',
            'country',
            'zipcode',
            'phone',
            'crm_id'
        ],
    ]) ?>

</div>
