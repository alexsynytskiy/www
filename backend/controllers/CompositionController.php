<?php

namespace backend\controllers;

use Yii;
use common\models\Team;
use common\models\Match;
use common\models\Contract;
use common\models\Membership;
use common\models\Composition;
use common\models\CompositionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * CompositionController implements the CRUD actions for Composition model.
 */
class CompositionController extends Controller
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
     * Lists all Composition models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompositionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Composition model.
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
     * Creates a new Composition model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($matchId = null, $teamId = null)
    {
        $model = new Composition();
        if(!isset($matchId) || !isset($teamId)) {
            throw new \yii\web\BadRequestHttpException('Unidentified matchId and teamId');
        }
        $match = Match::findOne($matchId);
        $team = Team::findOne($teamId);
        if(!isset($match) || !isset($team)) {
            throw new \yii\web\BadRequestHttpException('Unidentified match and team models');
        }
        $model->command_id = $teamId;
        $model->match_id = $matchId;
        $model->is_basis = 1;

        $contractTeams = Team::getContractTeams();
        if(in_array($teamId, $contractTeams)) {
            $model->contract_type = Composition::CONTRACT_TYPE;
            $contractModel = new Contract();
            $contractModel->season_id = $match->season_id;
            $contractModel->is_active = 1;
        } else {
            $model->contract_type = Composition::MEMBERSHIP_TYPE;
            $contractModel = new Membership();
        }
        $contractModel->command_id = $teamId;

        if ($model->load(Yii::$app->request->post()) &&
                $contractModel->load(Yii::$app->request->post()) && $contractModel->validate()) {

            if($contractModel->save(false)) {
                $model->contract_id = $contractModel->id;
                $model->number = $contractModel->number;
                $model->save();
            }
            if(Yii::$app->request->isAjax) {
                $out = ['success' => 'true'];
                return Json::encode($out);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            if(Yii::$app->request->isAjax) { 
                return $this->renderAjax('create', [
                    'model' => $model,
                    'contractModel' => $contractModel,
                ]);
            }
            return $this->render('create', [
                'model' => $model,
                'contractModel' => $contractModel,
            ]);
        }
    }

    /**
     * Updates an existing Composition model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->amplua_id = $model->getAmpluaId();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if(isset($model->contract)) {
                $model->contract->amplua_id = $model->amplua_id;
                $model->contract->save(false);
            }
            $model->is_substitution = $model->is_basis ? 0 : 1;
            $model->save(false);

            if(Yii::$app->request->isAjax) {
                $out = ['success' => 'true'];
                return Json::encode($out);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            if(Yii::$app->request->isAjax) { 
                return $this->renderAjax('update', [
                    'model' => $model,
                ]);
            }
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates list of players.
     * @return mixed
     */
    public function actionUpdateList()
    {
        $list = Yii::$app->request->post('list');
        $teamId = Yii::$app->request->post('teamId');
        $matchId = Yii::$app->request->post('matchId');
        if(isset($list) && $teamId && $matchId) {
            $list = explode(';', $list);
            $composition = (new \yii\db\Query())
                ->select(['contract_id'])
                ->from(Composition::tableName())
                ->where([
                    'match_id' => $matchId,
                    'command_id' => $teamId,
                ])->all();
            $contractIds = [];
            foreach ($composition as $data) {
                $contractIds[] = $data['contract_id'];
            }
            // Remove
            $removeList = [];
            foreach ($contractIds as $id) {
                if(is_numeric($id) && !in_array($id, $list)) {
                    $removeList[] = $id;
                }
            }
            if(count($removeList) > 0) {
                Composition::deleteAll([
                    'match_id' => $matchId,
                    'command_id' => $teamId,
                    'contract_id' => $removeList,
                ]);
            }
            // Add
            $contractTeams = Team::getContractTeams();
            if(in_array($teamId, $contractTeams)) {
                $contractType = Composition::CONTRACT_TYPE;
                $contractModel = new Contract();
            } else {
                $contractType = Composition::MEMBERSHIP_TYPE;
                $contractModel = new Membership();
            }
            $addList = [];
            foreach ($list as $id) {
                if(is_numeric($id) && !in_array($id, $contractIds)) {
                    // Add 
                    $addList[] = $id;
                    $contract = $contractModel::findOne($id);
                    $model = new Composition();
                    $model->contract_type = $contractType;
                    $model->contract_id = $id;
                    $model->command_id = $teamId;
                    $model->match_id = $matchId;
                    $model->is_basis = 1;
                    $model->is_substitution = 0;
                    $model->is_captain = 0;
                    if(isset($contract)) {
                        $model->number = $contract->number;
                        $model->amplua_id = $contract->amplua_id;
                    }
                    $model->save();
                }
            }
            $out = [
                'success' => 'true',
                'removeList' => $removeList,
                'addList' => $addList,
            ];
        } else {
            $out = [
                'success' => 'false',
                'list' => $list,
                'teamId' => $teamId,
                'match' => $matchId,
            ];
        }
        return Json::encode($out);
    }

    /**
     * Deletes an existing Composition model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        if(Yii::$app->request->isAjax) {
            $out = ['success' => 'true'];
            return Json::encode($out);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Composition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Composition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Composition::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
