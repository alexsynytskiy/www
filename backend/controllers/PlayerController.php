<?php

namespace backend\controllers;

use Yii;
use common\models\Player;
use common\models\PlayerSearch;
use common\models\Achievement;
use common\models\AchievementSearch;
use common\models\CareerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\db\Query;
use yii\web\UploadedFile;
use common\models\Asset;

/**
 * PlayerController implements the CRUD actions for Player model.
 */
class PlayerController extends Controller
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
     * Lists all Player models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlayerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Player model.
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
     * Creates a new Player model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Player();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $uploadedFile = UploadedFile::getInstance($model,'avatar');

            $model->slug = $model->genSlug();
            $model->birthday = date('Y-m-d', strtotime($model->birthday));
            $model->save(false);

            if(!empty($uploadedFile))
            {
                // Save origionals 
                $originalAsset = new Asset();
                $originalAsset->assetable_type = Asset::ASSETABLE_PLAYER;
                $originalAsset->assetable_id = $model->id;
                $originalAsset->uploadedFile = $uploadedFile;
                $originalAsset->saveAsset();

                // Save thumbnails 
                $imageID = $originalAsset->id;
                $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_PLAYER);

                foreach ($thumbnails as $thumbnail) {
                    $asset = new Asset();
                    $asset->assetable_type = Asset::ASSETABLE_PLAYER;
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
     * Updates an existing Player model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $image = $model->getAsset(Asset::THUMBNAIL_CONTENT);

        $achievementModel = new AchievementSearch();
        $params = ['AchievementSearch' => [
            'player_id' => $model->id,
        ]];
        $achievementDataProvider = $achievementModel->search($params);
        $model->birthday = date('d.m.Y', strtotime($model->birthday));

        $searchModel = new CareerSearch();

        // careerDataProvider
        $params = ['CareerSearch' => [
            'player_id' => $model->id,
        ]];
        $careerDataProvider = $searchModel->search($params);
        $careerDataProvider->setSort(['defaultOrder' => ['season_id' => SORT_DESC]]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) 
        {
            $uploadedFile = UploadedFile::getInstance($model,'avatar');

            // If image was uploaded
            if(!empty($uploadedFile))
            {
                // Save origionals 
                $originalAsset = $model->getAsset();
                if(!isset($originalAsset->id)) {
                    $originalAsset = new Asset();
                }
                $originalAsset->assetable_type = Asset::ASSETABLE_PLAYER;
                $originalAsset->assetable_id = $model->id;
                $originalAsset->uploadedFile = $uploadedFile;
                $originalAsset->saveAsset();

                // Save thumbnails 
                $imageID = $originalAsset->id;
                $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_PLAYER);

                foreach ($thumbnails as $thumbnail) {
                    $asset = $model->getAsset($thumbnail);
                    if(!isset($asset->id)) {
                        $asset = new Asset();
                    }
                    $asset->assetable_type = Asset::ASSETABLE_PLAYER;
                    $asset->assetable_id = $model->id;
                    $asset->parent_id = $imageID;
                    $asset->thumbnail = $thumbnail;
                    $asset->uploadedFile = $uploadedFile;
                    $asset->cropData = $model->cropData;
                    $asset->saveCroppedAsset();
                }
            }

            $model->slug = $model->genSlug();
            $model->birthday = date('Y-m-d', strtotime($model->birthday));
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
            'achievementModel' => $achievementModel,
            'achievementDataProvider' => $achievementDataProvider,
            'image' => $image,
            'careerDataProvider' => $careerDataProvider,
        ]);
        
    }

    /**
     * Display list of players in json format
     *
     * @param string $query Query for search
     * @return mixed Json data
     */
    public function actionPlayerList($query = null) {
        if($query == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $search = urldecode($query);
        $query = new Query;
        $query->select("id, firstname, lastname")
            ->from(Player::tableName())
            ->where(['like', 'lastname', $search])
            ->orWhere(['like', 'firstName', $search])
            ->orderBy('lastname')
            ->limit(10);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $data = array_values($data);
        $out = [];
        foreach ($data as $player) {
            $out[] = [
                'value' => $player['id'],
                'text' => $player['firstname'].' '.$player['lastname'],
            ];
        }
        header("Content-type: text/html; charset=utf-8");
        echo Json::encode($out);
    }

    /**
     * Deletes an existing Player model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $assets = Asset::getAssets($model->id, Asset::ASSETABLE_USER);
        foreach ($assets as $asset) {
            $asset->delete();
        }
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Player model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Player the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Player::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
