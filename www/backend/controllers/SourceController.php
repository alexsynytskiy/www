<?php

namespace backend\controllers;

use Yii;
use common\models\Source;
use common\models\SourceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\db\Query;

/**
 * SourceController implements the CRUD actions for Source model.
 */
class SourceController extends Controller
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
     * Lists all Source models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SourceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Source model.
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
     * Creates a new Source model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Source();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Source model.
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
     * Deletes an existing Source model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        if(Yii::$app->request->referrer){
            return $this->redirect(Yii::$app->request->referrer);
        }else{
            return $this->redirect(['index']);
        }
    }

    /**
     * Display list of all source names in json format
     *
     * @param string $q Query for search
     * @return mixed Json data
     */
    public function actionSourceNameList($q = null) {
        // if(mb_strlen($q,'UTF-8') < 2) return Json::encode([]);
        $query = new Query;
        $query->select('name as value')
            ->distinct()
            ->from(Source::tableName())
            ->where(['like', 'name', $q])
            ->orderBy('name');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = array_values($data);
        echo Json::encode($out);
    }

    /**
     * Display list of all source urls in json format
     *
     * @param string $q Query for search
     * @return mixed Json data
     */
    public function actionSourceUrlList($q = null) {
        // if(mb_strlen($q,'UTF-8') < 2) return Json::encode([]);
        $query = new Query;
        $query->select('url as value')
            ->distinct()
            ->from(Source::tableName())
            ->where(['like', 'url', $q])
            ->orderBy('url');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = array_values($data);
        echo Json::encode($out);
    }

    /**
     * Finds the Source model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Source the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Source::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
