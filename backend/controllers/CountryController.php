<?php

namespace backend\controllers;

use Yii;
use common\models\Country;
use common\models\CountrySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\db\Query;
use yii\web\UploadedFile;
use common\models\Asset;

/**
 * CountryController implements the CRUD actions for Country model.
 */
class CountryController extends Controller
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
     * Lists all Country models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CountrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Country model.
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
     * Creates a new Country model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Country();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $uploadedFile = UploadedFile::getInstance($model, 'flag');
            $model->save(false);

            if(!empty($uploadedFile))
            {
                // Save origionals 
                $originalAsset = new Asset();
                $originalAsset->assetable_type = Asset::ASSETABLE_COUNTRY;
                $originalAsset->assetable_id = $model->id;
                $originalAsset->uploadedFile = $uploadedFile;
                $originalAsset->saveAsset();

                // Save thumbnails 
                $imageID = $originalAsset->id;
                $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_COUNTRY);

                foreach ($thumbnails as $thumbnail) {
                    $asset = new Asset();
                    $asset->assetable_type = Asset::ASSETABLE_COUNTRY;
                    $asset->assetable_id = $model->id;
                    $asset->parent_id = $imageID;
                    $asset->thumbnail = $thumbnail;
                    $asset->uploadedFile = $uploadedFile;
                    $asset->saveAsset();
                }
            }
                        
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Country model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $flag = $model->getAsset();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $uploadedFile = UploadedFile::getInstance($model, 'flag');

            // If image was uploaded
            if(!empty($uploadedFile))
            {
                // Save origionals 
                $originalAsset = $model->getAsset();
                if(!isset($originalAsset->id)) {
                    $originalAsset = new Asset();
                }
                $originalAsset->assetable_type = Asset::ASSETABLE_COUNTRY;
                $originalAsset->assetable_id = $model->id;
                $originalAsset->uploadedFile = $uploadedFile;
                $originalAsset->saveAsset();

                // Save thumbnails 
                $imageID = $originalAsset->id;
                $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_COUNTRY);

                foreach ($thumbnails as $thumbnail) {
                    $asset = $model->getAsset($thumbnail);
                    if(!isset($asset->id)) {
                        $asset = new Asset();
                    }
                    $asset->assetable_type = Asset::ASSETABLE_COUNTRY;
                    $asset->assetable_id = $model->id;
                    $asset->parent_id = $imageID;
                    $asset->thumbnail = $thumbnail;
                    $asset->uploadedFile = $uploadedFile;
                    $asset->saveAsset();
                }
            }

            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'flag' => $flag,
        ]);
    }

    /**
     * Deletes an existing Country model.
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
     * Finds the Country model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Country the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Country::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Display list of countries in json format
     *
     * @param string $q Query for search
     * @return mixed Json data
     */
    public function actionCountryPartList($query = null) {
        if($query == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $search = urldecode($query);
        $query = new Query;
        $query->select('id as value, name as text')
            ->from(Country::tableName())
            ->where(['like', 'name', $search])
            ->orderBy(['name' => SORT_ASC])
            ->limit(10);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = array_values($data);
        header("Content-type: text/html; charset=utf-8");
        echo Json::encode($out);
    }
}
