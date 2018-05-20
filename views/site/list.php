<?php
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\web\UrlManager;

$dataProvider = new ArrayDataProvider([
    'key'=>'u_id',
    'allModels' => $data,
    'pagination' => [ 'pageSize' => 40 ],
    'sort' => [
        'attributes' => ['id', 'unique_identifier', 'name'],
    ],

]);


?>

<h1>PAP Affiliate Manager</h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,

    'columns' => [
        ['class' => 'yii\grid\ActionColumn',
          'template'=>'{view}',
            'buttons' => [
               'view' => function ($url,$key) {

                    return Html::a('Details', $url);
                },
            ],
            'urlCreator'    => function ($action, $model, $key, $index) {
                return \yii\helpers\Url::to(['/site/pap-detail','id'=>$key]);
            },
            ],
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


?>