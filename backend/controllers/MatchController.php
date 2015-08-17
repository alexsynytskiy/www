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
use yii\helpers\ArrayHelper;

use common\models\CompositionForm;
use common\models\CompositionSearch;
use common\models\MatchEventSearch;
use common\models\MatchEventType;
use common\models\MatchEvent;
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
        $homeCompositionDataProvider->setSort(['defaultOrder' => ['is_basis' => SORT_DESC, 'number' => SORT_ASC]]);

        // guestCompositionDataProvider
        $params = ['CompositionSearch' => [
            'match_id' => $model->id,
            'command_id' => $model->command_guest_id,
        ]];
        $guestCompositionDataProvider = $searchModel->search($params);
        $guestCompositionDataProvider->setSort(['defaultOrder' => ['is_basis' => SORT_DESC, 'number' => SORT_ASC]]);

        $contractTeams = Team::getContractTeams();

        // homeComposition
        if(in_array($model->command_home_id, $contractTeams)){
            $homeContractType = CompositionForm::CONTRACT_TYPE;
            $homeCompositionData = Contract::find()
                ->where([
                    'command_id' => $model->command_home_id,
                    'season_id' => $model->season_id,
                    'is_active' => 1,
                ])->orderBy(['number' => SORT_ASC])->all();
        } else {
            $homeContractType = CompositionForm::MEMBERSHIP_TYPE;
            $homeCompositionData = Membership::find()
                ->where([
                    'command_id' => $model->command_home_id,
                ])
                ->orderBy(['number' => SORT_ASC])->all();
        }
        $homeComposition = [];
        foreach ($homeCompositionData as $key => $data) {
            $homeComposition[$key]['id'] = $data->id;
            $homeComposition[$key]['name'] = "#".$data->number." ".$data->player->lastname." ".$data->player->firstname;
        }

        // guestComposition
        if(in_array($model->command_guest_id, $contractTeams)){
            $guestContractType = CompositionForm::CONTRACT_TYPE;
            $guestCompositionData = Contract::find()
                ->where([
                    'command_id' => $model->command_guest_id,
                    'season_id' => $model->season_id,
                    'is_active' => 1,
                ])->orderBy(['number' => SORT_ASC])->all();
        } else {
            $guestContractType = CompositionForm::MEMBERSHIP_TYPE;
            $guestCompositionData = Membership::find()
                ->where([
                    'command_id' => $model->command_guest_id,
                ])->orderBy(['number' => SORT_ASC])->all();
        }
        $guestComposition = [];
        foreach ($guestCompositionData as $key => $data) {
            $guestComposition[$key]['id'] = $data->id;
            $guestComposition[$key]['name'] = "#".$data->number." ".$data->player->lastname." ".$data->player->firstname;
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
     * Updates statistics of an existing Match model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionStatUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);

            $previousURL = Yii::$app->request->referrer;

            if(strpos($previousURL, 'admin/match/events') !== false) {
                return $this->redirect(['match/events', 'id' => $model->id]);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('stat_update', compact('model'));
        }
    }

    /**
     * Updates statistics of an existing Match model.
     * @param integer $id
     * @return mixed
     */
    public function actionEvents($id)
    {
        $model = $this->findModel($id);

        $matchEventModel = new MatchEvent();
        $matchEventModel->match_id = $model->id;

        $matchEventModelSearch = new MatchEventSearch();

        $params = ['MatchEventSearch' => [
            'match_id' => $model->id,
        ]];
        $matchEventDataProvider = $matchEventModelSearch->search($params);
//        $totalCount = $matchEventDataProvider->getTotalCount();
//        $matchEventDataProvider->pagination = ['defaultPageSize' => 60];
        $matchEventDataProvider->setSort(['defaultOrder' => ['minute' => SORT_DESC, 'additional_minute' => SORT_DESC]]);

        $matchEvents = MatchEventType::find()->all();
        $eventFilter = ArrayHelper::map($matchEvents, 'id', 'name');

        $searchModel = new CompositionSearch();

        // homeCompositionDataProvider
        $params = ['CompositionSearch' => [
            'match_id' => $model->id,
            'command_id' => $model->command_home_id,
        ]];
        $homeCompositionDataProvider = $searchModel->search($params);
        $homeCompositionDataProvider->setSort(['defaultOrder' => ['is_basis' => SORT_DESC, 'number' => SORT_ASC]]);

        // guestCompositionDataProvider
        $params = ['CompositionSearch' => [
            'match_id' => $model->id,
            'command_id' => $model->command_guest_id,
        ]];
        $guestCompositionDataProvider = $searchModel->search($params);
        $guestCompositionDataProvider->setSort(['defaultOrder' => ['is_basis' => SORT_DESC, 'number' => SORT_ASC]]);

        if ($matchEventModel->load(Yii::$app->request->post()) && $matchEventModel->validate()) {
            $matchEventModel->save(false);
            $this->redirect(['match/events', 'id' => $model->id]);
        }
        return $this->render('match_events', compact(
            'model',
            'matchEventModel',
            'matchEventModelSearch',
            'matchEventDataProvider',
            'eventFilter',
            'homeCompositionDataProvider',
            'guestCompositionDataProvider'
        ));

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

    /**
     * @param integer $matchID
     * @return Json Name of match
     */
    public function actionMatchName($matchID)
    {
        $model = Match::findOne($matchID);
        if(!isset($model->id)) return Json::encode(['data' => 'Матч не найден']);
        $date = date('d.m.Y', strtotime($model->date));
        return Json::encode(['data' => $model->name.' ('.$date.')']);
    }

    /**
     * @param array $query
     * @return Json Name of match
     */
    public function actionMatchList()
    {
        $searchModel = new MatchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $matches = $dataProvider->getModels();
        $matchesList = [];
        foreach ($matches as $match) {
            $matchDate = date('d.m.Y', strtotime($match->date));
            $matchesList[(int)$match->id] = $match->name.' ('.$matchDate.')';
        }
        if(!count($matchesList)) return Json::encode(['success' => false, 'message' => 'Матчей не найдено']);
        return Json::encode(['success' => true, 'list' => $matchesList]);
    }
}
