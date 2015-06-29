<?php

namespace backend\controllers;

use Yii;
use common\models\Team;
use common\models\TeamSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\db\Query;
use yii\web\UploadedFile;
use common\models\Asset;

/**
 * TeamController implements the CRUD actions for Team model.
 */
class TeamsController extends Controller
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
     * Lists all Team models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TeamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Team model.
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
     * Creates a new Team model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Team();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $uploadedFile = UploadedFile::getInstance($model,'icon');
            $model->save(false);

            if(!empty($uploadedFile))
            {
                $asset = new Asset;
                $asset->assetable_type = Asset::ASSETABLE_TEAM;
                $asset->assetable_id = $model->id;
                $asset->uploadedFile = $uploadedFile;
                $asset->saveAsset();
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Team model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $asset = $model->getAsset();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $uploadedFile = UploadedFile::getInstance($model,'icon');

            // If image was uploaded
            if(!empty($uploadedFile))
            {
                // If asset model did't exist for current model
                if(!isset($asset->assetable_id))
                {
                    $asset = new Asset;
                    $asset->assetable_type = Asset::ASSETABLE_TEAM;
                    $asset->assetable_id = $model->id;
                }
                $asset->uploadedFile = $uploadedFile;
                $asset->saveAsset();
            }

            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }
    
        /**
     * Display list of teams in json format
     *
     * @param string $query Query for search
     * @return mixed Json data
     */
    public function actionTeamList($query = null) {
        if($query == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $search = urldecode($query);
        $query = new Query;
        $query->select('id as value, name as text')
            ->from(Team::tableName())
            ->where(['like', 'name', $search])
            ->orderBy('name')
            ->limit(10);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = array_values($data);
        header("Content-type: text/html; charset=utf-8");
        echo Json::encode($out);
    }

    /**
     * Deletes an existing Team model.
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
     * Finds the Team model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Team the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Team::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
