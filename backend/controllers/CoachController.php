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
        $model = $this->findModel($id);
        $photo = $model->getAsset(Asset::THUMBNAIL_CONTENT);
        return $this->render('view', [
            'model' => $model,
            'photo' => $photo,
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
                // Save origionals 
                $originalAsset = new Asset();
                $originalAsset->assetable_type = Asset::ASSETABLE_COACH;
                $originalAsset->assetable_id = $model->id;
                $originalAsset->uploadedFile = $uploadedFile;
                $originalAsset->saveAsset();

                // Save thumbnails 
                $imageID = $originalAsset->id;
                $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_COACH);

                foreach ($thumbnails as $thumbnail) {
                    $asset = new Asset();
                    $asset->assetable_type = Asset::ASSETABLE_COACH;
                    $asset->assetable_id = $model->id;
                    $asset->parent_id = $imageID;
                    $asset->thumbnail = $thumbnail;
                    $asset->uploadedFile = $uploadedFile;
                    $asset->cropData = $model->cropData;
                    $asset->saveCroppedAsset();
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
     * Updates an existing Coach model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $photo = $model->getAsset(Asset::THUMBNAIL_CONTENT);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $uploadedFile = UploadedFile::getInstance($model, 'photo');           

            // If image was uploaded
            if(!empty($uploadedFile))
            {
                // Save origionals 
                $originalAsset = $model->getAsset();
                if(!isset($originalAsset->id)) {
                    $originalAsset = new Asset();
                }
                $originalAsset->assetable_type = Asset::ASSETABLE_COACH;
                $originalAsset->assetable_id = $model->id;
                $originalAsset->uploadedFile = $uploadedFile;
                $originalAsset->saveAsset();

                // Save thumbnails 
                $imageID = $originalAsset->id;
                $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_COACH);

                foreach ($thumbnails as $thumbnail) {
                    $asset = $model->getAsset($thumbnail);
                    if(!isset($asset->id)) {
                        $asset = new Asset();
                    }
                    $asset->assetable_type = Asset::ASSETABLE_COACH;
                    $asset->assetable_id = $model->id;
                    $asset->parent_id = $imageID;
                    $asset->thumbnail = $thumbnail;
                    $asset->uploadedFile = $uploadedFile;
                    $asset->cropData = $model->cropData;
                    $asset->saveCroppedAsset();
                }
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
        $model = $this->findModel($id);
        $assets = Asset::getAssets($model->id, Asset::ASSETABLE_USER, NULL);
        foreach ($assets as $asset) {
            $asset->delete();
        }
        $model->delete();
        return $this->redirect(['index']);
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
     * Display list of coaches in json format
     *
     * @param string $query Query for search
     * @return mixed Json data
     */
    public function actionCoachList($query = null) {
        if($query == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $search = urldecode($query);
        $query = new Query;
        $query->select("id, name")
            ->from(Coach::tableName())
            ->where(['like', 'name', $search])
            ->orderBy('name')
            ->limit(10);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $data = array_values($data);
        $out = [];
        foreach ($data as $coach) {
            $out[] = [
                'value' => $coach['id'],
                'text' => $coach['name'],
            ];
        }
        header("Content-type: text/html; charset=utf-8");
        echo Json::encode($out);
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
