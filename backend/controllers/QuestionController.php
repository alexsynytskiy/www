<?php

namespace backend\controllers;

use Yii;
use common\models\Question;
use common\models\QuestionSearch;
use common\models\QuestionVote;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * QuestionController implements the CRUD actions for Question model.
 */
class QuestionController extends Controller
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
     * Lists all Question models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QuestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Question model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $answerForm = new Question();
        $searchModel = new QuestionSearch();
        $params = ['QuestionSearch' => [
            'parent_id' => $model->id,
        ]];
        $answersDataProvider = $searchModel->search($params);
        $answersDataProvider->setSort(['defaultOrder' => ['id' => SORT_ASC]]);

        return $this->render('view', [
            'model' => $model,
            'answerForm' => $answerForm,
            'answersDataProvider' => $answersDataProvider,
        ]);
    }

    /**
     * Creates a new Question model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Question();
        $parentId = Yii::$app->request->get('parent_id');

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);
            if(Yii::$app->request->isAjax) {
                $out = ['success' => 'true'];
                return Json::encode($out);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            if(isset($parentId)) $model->parent_id = $parentId;
            if(Yii::$app->request->isAjax) { 
                return $this->renderAjax('create', [
                    'model' => $model,
                ]);
            }
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Question model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(!Yii::$app->request->isAjax) {
            $answerForm = new Question();
            $searchModel = new QuestionSearch();
            $params = ['QuestionSearch' => [
                'parent_id' => $model->id,
            ]];
            $answersDataProvider = $searchModel->search($params);
            $answersDataProvider->setSort(['defaultOrder' => ['id' => SORT_DESC]]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
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
                    'answerForm' => null,
                    'answersDataProvider' => null,
                ]);
            }
            return $this->render('update', [
                'model' => $model,
                'answerForm' => $answerForm,
                'answersDataProvider' => $answersDataProvider,
            ]);
        }
    }

    /**
     * Deletes an existing Question model.
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
     * Vote for selected answer
     * @return mixed Json
     */
    public function actionVote()
    {
        $aid = Yii::$app->request->get('answer');
        $userID = isset(Yii::$app->user->id) ? Yii::$app->user->id : false;
        if($aid && $userID) {
            $answer = $this->findModel($aid);
            if(isset($answer->id) && !is_null($answer->parent_id)) {
                $vote = new QuestionVote();
                $vote->question_id = $answer->parent_id;
                $vote->user_id = $userID;
                if($vote->save()) {
                    $answer->voutes++;
                    $answer->save(false);
                    return Json::encode(['success' => true]);
                }
            }
        }
        return Json::encode(['success' => false]);
    }

    /**
     * Vote for selected float answer
     * @return mixed Json
     */
    public function actionVoteFloat()
    {
        $answerValues = Yii::$app->request->get('answer');
        $userID = isset(Yii::$app->user->id) ? Yii::$app->user->id : false;

        // validation
        $answers = [];
        if(is_array($answerValues) && $userID) {
            foreach ($answerValues as $aid => $value) {
                $answer = $this->findModel($aid);
                if(isset($answer)) $answers[] = $answer;
            }
        }
        if(count($answers) == 0) return Json::encode(['success' => false, 'msg' => 'answers']);
        $first = $answers[0];
        $question = $this->findModel($first->parent_id);

        if(isset($question)){
            $question->voutes++;
            $vote = new QuestionVote();
            $vote->question_id = $answer->parent_id;
            $vote->user_id = $userID;
            if(!$vote->validate() || !$question->validate()) {
                return Json::encode(['success' => false, 'msg' => 'vote']);
            }
            $vote->save();
            $question->save();
            foreach ($answers as $answer) {
                $answer->mark = round(($value + $answer->mark * ($question->voutes - 1))/$question->voutes, 4);
                $answer->save();
            }
            return Json::encode(['success' => true]);
        }
                
        return Json::encode(['success' => false]);
    }

    /**
     * Vote for selected float answer
     * @param $matchID int
     * @return mixed Json
     */
    public function actionAutogen($matchID)
    {
        $match = \common\models\Match::findOne($matchID);
        
        if(!isset($match)) {
            throw new NotFoundHttpException('Страница не найдена.');
        }

        if(in_array($match->teamHome->id, \common\models\Team::getTeamsConstants())) {
            $ourTeam = $match->teamHome;
            $opponentTeam = $match->teamGuest;
        } else {
            $ourTeam = $match->teamGuest;
            $opponentTeam = $match->teamHome;
        }

        $basisPlayers = \common\models\Composition::find()
            ->where([
                'match_id' => $match->id, 
                'command_id' => $ourTeam->id, 
                'is_basis' => 1,
            ])
            ->all();

        $compositionTable = \common\models\Composition::tableName();
        $matchEventTable = \common\models\MatchEvent::tableName();
        $substitutionPlayers = \common\models\Composition::find()
            ->innerJoin($matchEventTable, "$matchEventTable.substitution_id = $compositionTable.id")
            ->where([
                $compositionTable.'.match_id' => $match->id, 
                'command_id' => $ourTeam->id, 
            ])
            ->all();

        $teamPlayers = array_merge($basisPlayers, $substitutionPlayers);

        $question = new Question();
        $question->title = 'Оценки игрокам '.$ourTeam->name.' в матче с '.$opponentTeam->name;
        $question->voutes = 0;
        $question->is_active = 1;
        $question->is_float = 1;
        $question->is_multipart = 0;
        $question->mark = 0;

        if($question->save()) {
            foreach ($teamPlayers as $teamPlayer) {
                $answer = new Question();
                $answer->parent_id = $question->id;
                $answer->title = $teamPlayer->name;
                $answer->mark = 0;
                $answer->save();
            }
        }
        
        return $this->redirect(['view', 'id' => $question->id]);
    }

    /**
     * Finds the Question model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Question the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Question::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
