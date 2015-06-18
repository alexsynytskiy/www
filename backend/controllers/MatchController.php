<?php

namespace backend\controllers;

use Yii;
use common\models\Match;
use common\models\MatchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
            $model->save(FALSE);
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

        $model->date = date('d.m.Y',strtotime($model->date));

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
            $homeCompositionData = Contract::find()
                ->where([
                    'command_id' => $model->command_home_id,
                    'season_id' => $model->season_id,
                ])->all();
        } else {
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
            $guestCompositionData = Contract::find()
                ->where([
                    'command_id' => $model->command_guest_id,
                    'season_id' => $model->season_id,
                ])->all();
        } else {
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

            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', compact(
                'model', 
                'compositionForm',
                'homeComposition', 
                'guestComposition',
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
}
