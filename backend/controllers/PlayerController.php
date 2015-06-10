<?php

namespace backend\controllers;

use Yii;
use common\models\Player;
use common\models\PlayerSearch;
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

            $model->avatar = UploadedFile::getInstance($model,'avatar');

            $model->slug = $model->genSlug();
            $model->save(false);

            if(!empty($model->avatar))
            {
                $asset = new Asset;
                $asset->assetable_type = Asset::ASSETABLE_PLAYER;
                $asset->assetable_id = $model->id;
                $asset->uploadedFile = $model->avatar;
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
     * Updates an existing Player model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $asset = $model->getAsset();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->avatar = UploadedFile::getInstance($model,'avatar');

            // If image was uploaded
            if(!empty($model->avatar))
            {
                // If asset model did't exist for current model
                if(!isset($asset->assetable_id))
                {
                    $asset = new Asset;
                    $asset->assetable_type = Asset::ASSETABLE_PLAYER;
                    $asset->assetable_id = $model->id;
                }

                $asset->uploadedFile = $model->avatar;
                $asset->cropData = $model->cropData;

                $asset->saveCroppedAsset();
            }

            $model->slug = $model->genSlug();
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
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
        $asset = $model->getAsset();
        $asset->delete();
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
