<?php

namespace backend\controllers;

use Yii;
use common\models\Match;
use common\models\MatchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\db\Query;

use common\models\CompositionForm;
use common\models\CompositionSearch;
use common\models\Membership;
use common\models\Contract;
use common\models\Team;

/**
 * MatchController implements the CRUD actions for Match model.
 */
class MatchController extends Controller
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
     * Lists all Match models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MatchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Match model.
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
     * Creates a new Match model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Match();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->date = date('Y-m-d H:i', strtotime($model->date));
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Match model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->date = date('d.m.Y H:i',strtotime($model->date));

        // compositionForm
        $compositionForm = new CompositionForm();
        $compositionForm->match_id = $model->id;
        $compositionForm->initPlayers($model->command_home_id, $model->command_guest_id);

        $searchModel = new CompositionSearch();

        // homeCompositionDataProvider
        $params = ['CompositionSearch' => [
            'match_id' => $model->id,
            'command_id' => $model->command_home_id,
        ]];
        $homeCompositionDataProvider = $searchModel->search($params);
        $homeCompositionDataProvider->setSort(['defaultOrder' => ['is_basis' => SORT_DESC]]);

        // guestCompositionDataProvider
        $params = ['CompositionSearch' => [
            'match_id' => $model->id,
            'command_id' => $model->command_guest_id,
        ]];
        $guestCompositionDataProvider = $searchModel->search($params);
        $guestCompositionDataProvider->setSort(['defaultOrder' => ['is_basis' => SORT_DESC]]);

        $contractTeams = Team::getContractTeams();

        // homeComposition
        if(in_array($model->command_home_id, $contractTeams)){
            $homeContractType = CompositionForm::CONTRACT_TYPE;
            $homeCompositionData = Contract::find()
                ->where([
                    'command_id' => $model->command_home_id,
                    'season_id' => $model->season_id,
                ])->all();
        } else {
            $homeContractType = CompositionForm::MEMBERSHIP_TYPE;
            $homeCompositionData = Membership::find()
                ->where([
                    'command_id' => $model->command_home_id,
                ])->all();
        }
        $homeComposition = [];
        foreach ($homeCompositionData as $key => $data) {
            $homeComposition[$key]['id'] = $data->id;
            $homeComposition[$key]['name'] = $data->name;
        }

        // guestComposition
        if(in_array($model->command_guest_id, $contractTeams)){
            $guestContractType = CompositionForm::CONTRACT_TYPE;
            $guestCompositionData = Contract::find()
                ->where([
                    'command_id' => $model->command_guest_id,
                    'season_id' => $model->season_id,
                ])->all();
        } else {
            $guestContractType = CompositionForm::MEMBERSHIP_TYPE;
            $guestCompositionData = Membership::find()
                ->where([
                    'command_id' => $model->command_guest_id,
                ])->all();
        }
        $guestComposition = [];
        foreach ($guestCompositionData as $key => $data) {
            $guestComposition[$key]['id'] = $data->id;
            $guestComposition[$key]['name'] = $data->name;
        }


        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->date = date('Y-m-d H:i', strtotime($model->date));
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', compact(
                'model', 
                'compositionForm',
                'homeComposition', 
                'guestComposition',
                'homeContractType',
                'guestContractType',
                'homeCompositionDataProvider',
                'guestCompositionDataProvider'
            ));
        }
    }

    /**
     * Deletes an existing Match model.
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
     * Finds the Match model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Match the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Match::findOne($id)) !== null) {
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
    public function actionMatchPartList($query = null) {
        if($query == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $search = urldecode($query);
        $query = new Query;
        $query->select('id as value, name as text')
            ->from(Match::tableName())
            ->where(['like', 'name', $search])
            ->orderBy('name')
            ->limit(10);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = array_values($data);
        header("Content-type: text/html; charset=utf-8");
        echo Json::encode($out);
    }
}
