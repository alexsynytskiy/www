<?php

namespace backend\controllers;

use Yii;
use common\models\Coach;
use common\models\CoachSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\db\Query;
use yii\web\UploadedFile;
use common\models\Asset;

/**
 * CoachController implements the CRUD actions for Coach model.
 */
class CoachController extends Controller
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
     * Lists all Coach models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CoachSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Coach model.
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
     * Creates a new Coach model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Coach();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $uploadedFile = UploadedFile::getInstance($model, 'photo');
            $model->slug = $model->genSlug();
            $model->save(false);

            if(!empty($uploadedFile))
            {
                $asset = new Asset;
                $asset->assetable_type = Asset::ASSETABLE_COACH;
                $asset->assetable_id = $model->id;
                $asset->uploadedFile = $uploadedFile;
                $asset->cropData = $model->cropData;
                $asset->saveCroppedAsset();
            }
                        
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Coach model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $photo = $model->getAsset();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->photo = UploadedFile::getInstance($model, 'photo');

            // If image was uploaded
            if(!empty($model->photo))
            {
                // If asset model did't exist for current model
                if(!isset($photo->assetable_id))
                {
                    $photo = new Asset;
                    $photo->assetable_type = Asset::ASSETABLE_COACH;
                    $photo->assetable_id = $model->id;
                }

                $photo->uploadedFile = $uploadedFile;
                $photo->cropData = $model->cropData;

                $photo->saveCroppedAsset();

                var_dump($photo->getErrors());
                die;
            }

            $model->slug = $model->genSlug();
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'photo' => $photo,
        ]);
    }

    /**
     * Deletes an existing Coach model.
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
     * Finds the Coach model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Coach the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Coach::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
