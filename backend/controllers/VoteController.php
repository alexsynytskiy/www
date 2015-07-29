<?php

namespace backend\controllers;

use Yii;
use common\models\Vote;
use common\models\VoteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * VoteController implements the CRUD actions for Vote model.
 */
class VoteController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    // 'vote' => ['post'],
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
     * Lists all Vote models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VoteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Vote model.
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
     * Creates a new Vote model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Vote();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Vote model.
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
     * Deletes an existing Vote model.
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
     * Add a new vote
     * @param integer $id
     * @param string $type
     * @param integer $vote
     * @return mixed
     */
    public function actionVote($id, $type, $vote = 1)
    {
        if(!Yii::$app->user->isGuest) 
        {
            $voteModel = Vote::find()->where([
                'voteable_type' => $type,
                'voteable_id' => $id,
                'user_id' => Yii::$app->user->id,
            ])->one();
            if(!isset($voteModel->id))
            {
                $voteModel = new Vote;
                $voteModel->voteable_type = $type;
                $voteModel->voteable_id = $id;
                $voteModel->user_id = Yii::$app->user->id;
            } elseif ($voteModel->vote == $vote) {
                $out = ['success' => false, 'error' => 'User has already voted'];
                echo Json::encode($out);
                return;
            } 
            $voteModel->vote = $vote;
            if($voteModel->save())
            {
                $rating = Vote::getRating($id, $type);
                $out = ['success' => true, 'rating' => $rating];
            } else {
                $out = ['success' => false, 'error' => $voteModel->getErrors()];
            }
        } else {
            $out = ['success' => false, 'error' => 'User is guest'];
        }
        echo Json::encode($out);
    }

    /**
     * Finds the Vote model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Vote the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vote::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
