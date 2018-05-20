<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use rmrevin\yii\fontawesome\FA;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\AffiliatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Affiliates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="affiliates-index">

	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
		<?= Html::button('Sync', ['class' => 'btn btn-success', 'id' => 'sync_affiliates_grid']) ?>
		<?= Html::button('Update CRM', ['class' => 'btn btn-info', 'id' => 'update_crm', 'disabled' => 'disabled']) ?>
    </p>
	<?php Pjax::begin(); ?>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			[
				'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function($model){
                    if(!empty($model->crm_id)){
						return ['disabled' => true, 'id' => 'affiliate_'.$model->id, 'class' => 'affiliate-checkbox'];
                    } else {
						return ['id' => 'affiliate_'.$model->id, 'class' => 'affiliate-checkbox'];
                    }
                }
			],
			'username',
			'name',
			'lastname',
			'phone',
			'refid',
			'rstatus',
			'street',
			'city',
			'state',
			'country',
			'crm_id',
//			'external_id',
			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>
	<?php Pjax::end(); ?></div>