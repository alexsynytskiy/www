<?php

namespace backend\controllers;

use Yii;
use common\models\Tournament;
use common\models\TournamentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use common\models\Season;
use common\models\League;

/**
 * TournamentController implements the CRUD actions for Tournament model.
 */
class TournamentController extends Controller
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
     * Lists all Tournament models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TournamentSearch();
        $tournamentTable = $searchModel::tableName();

        $seasonTable = Season::tableName();
        $seasons = Season::find()
            ->innerJoin($tournamentTable, "{$tournamentTable}.season_id = {$seasonTable}.id")
            ->orderBy(['name' => SORT_DESC])
            ->all();
        $availableSeasons = ArrayHelper::map($seasons, 'id', 'name');

        $leagueTable = League::tableName();
        $leagues = League::find()
            ->innerJoin($tournamentTable, "{$tournamentTable}.league_id = {$leagueTable}.id")
            ->all();
        $availableLeagues = ArrayHelper::map($leagues, 'id', 'name');

        $queryParams = Yii::$app->request->queryParams;
        if(count($queryParams) == 0) {
            $queryParams['TournamentSearch']['season_id'] = array_keys($availableSeasons)[0];
        }
        $dataProvider = $searchModel->search($queryParams);

        

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'availableSeasons' => $availableSeasons,
            'availableLeagues' => $availableLeagues,
        ]);
    }

    /**
     * Displays a single Tournament model.
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
     * Creates a new Tournament model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tournament();
        if(empty($model->weight)) $model->weight = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Tournament model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if(empty($model->won)) $model->won = 0;
            if(empty($model->draw)) $model->draw = 0;
            if(empty($model->lost)) $model->lost = 0;
            if(empty($model->penalty_points)) $model->penalty_points = 0;
            $model->penalty_points = abs($model->penalty_points);
            if(empty($model->goals_for)) $model->goals_for = 0;
            if(empty($model->weight)) $model->weight = 0;
            if(empty($model->goals_against)) $model->goals_against = 0;

            $model->played = $model->won + $model->draw + $model->lost;
            $model->points = $model->won * 3 + $model->draw - $model->penalty_points;

            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Tournament model.
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
     * Finds the Tournament model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tournament the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tournament::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
