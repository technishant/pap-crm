<?php

namespace app\controllers;

use app\components\CrmManager;
use Yii;
use app\models\Affiliates;
use app\models\search\AffiliatesSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\PAPManager;

/**
 * AffiliatesController implements the CRUD actions for Affiliates model.
 */
class AffiliatesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Affiliates models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AffiliatesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Affiliates model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Affiliates model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		try {
			$papManager = new PAPManager(1000);
			$list = $papManager->getAffiliateList();
			if(!empty($list)){
				foreach ($list as $affiliate){
					$model = new Affiliates();
					$model->mapAffiliateToModel($affiliate);
					if(!$model->sync()){
						echo "<pre>"; print_r($model->getErrors());die;
					};
				}
			}
		} catch (ErrorException $ex) {
			echo $ex->getMessage();
		}
    }

    /**
     * Updates an existing Affiliates model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Affiliates model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Affiliates model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Affiliates the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Affiliates::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAddToCrm(){
		if(!Yii::$app->request->isAjax){
			throw new HttpException(404, 'Bad Request');
		}
		$data = Yii::$app->request->post('selection');
		$res = [];
		if(!empty($data)){
			foreach ($data as $row){
				try {
					$model = Affiliates::findOne($row);
					if(!empty($model)){
						$crmManager = new CrmManager($model->mapModelToCrm());
						$response = $crmManager->addContact();
						$model->crm_id = !empty($response->id) ? $response->id : null;
						$model->save(false);
						$res[] = ['id' => $row, 'status' => true, 'error' => ""];
					}
				} catch(\ErrorException $ex){
					$res[] = ['id' => $row, 'status' => false, 'error' => $ex->getMessage()];
				}
			}
		}
		echo Json::encode($res);
	}
}
