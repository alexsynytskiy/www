<?php

namespace backend\controllers;

use Yii;
use common\models\MatchEventType;
use common\models\MatchEventTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\db\Query;
use yii\web\UploadedFile;
use common\models\Asset;

/**
 * MatchEventTypeController implements the CRUD actions for MatchEventType model.
 */
class MatchEventTypeController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->enableCsrfValidation = false;
        if (!empty(Yii::$app->user) && !Yii::$app->user->can("admin")) {
            throw new \yii\web\ForbiddenHttpException('Вы не можете выполнить это действие.');
        }

        parent::init();
    }

    /**
     * Lists all MatchEventType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MatchEventTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MatchEventType model.
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
     * Creates a new MatchEventType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MatchEventType();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $uploadedFile = UploadedFile::getInstance($model,'icon');
            $model->save(false);

            if(!empty($uploadedFile))
            {
                $icon = new Asset;
                $icon->assetable_type = Asset::ASSETABLE_MATCH_EVENT;
                $icon->assetable_id = $model->id;
                $icon->uploadedFile = $uploadedFile;
                $icon->saveAsset();
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MatchEventType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $icon = $model->getAsset();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            $model->icon = UploadedFile::getInstance($model,'icon');

            // If image was uploaded
            if(!empty($model->icon))
            {
                // If asset model did't exist for current model
                if(!isset($icon->assetable_id))
                {
                    $icon = new Asset;
                    $icon->assetable_type = Asset::ASSETABLE_MATCH_EVENT;
                    $icon->assetable_id = $model->id;
                }

                $icon->uploadedFile = $model->icon;
                $icon->saveAsset();
            }

            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'icon' => $icon,
        ]);
    }

    /**
     * Deletes an existing MatchEventType model.
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
     * Finds the MatchEventType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MatchEventType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MatchEventType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
