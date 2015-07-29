<?php

namespace backend\controllers;

use Yii;
use common\models\Subscribing;
use common\models\SubscribingSearch;
use common\models\Post;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * SubscribingController implements the CRUD actions for Subscribing model.
 */
class SubscribingController extends Controller
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
     * Lists all Subscribing models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubscribingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Subscribing model.
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
     * Creates a new Subscribing model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Subscribing();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Subscribing model.
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
     * Deletes an existing Subscribing model.
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
     * Sending emails to subscribers
     * @return mixed Content
     */
    public function actionSubscribeSend()
    {
        // $currentDayTime = strtotime(date('d.m.Y', time()));
        // $currentDay = date("Y-m-d H:i:s", $currentDayTime);
        // $importantPosts = Post::find()
        //     ->where([
        //         'is_public' => 1, 
        //         'is_index' => 1,
        //     ])
        //     ->andWhere(['>', 'created_at', $currentDay])
        //     ->orderBy(['created_at' => SORT_DESC])
        //     ->limit(3)->all();
        // $ids = [];
        // foreach ($importantPosts as $post) {
        //     $ids[] = $post->id;
        // }

        // $maxCommentsPosts = Post::find()
        //     ->where([
        //         'is_public' => 1, 
        //     ])
        //     ->andWhere(['>', 'created_at', $currentDay])
        //     ->andWhere(['not in', "id", $ids])
        //     ->orderBy([
        //         'comments_count' => SORT_DESC,
        //     ])
        //     ->limit(3)->all();
        // $posts = array_merge($importantPosts ,$maxCommentsPosts);

        // // sending
        // $subscribings = Subscribing::find()->all();
        // foreach ($subscribings as $subscribing) {
        //     if(!filter_var($subscribing->email, FILTER_VALIDATE_EMAIL)) {
        //         // var_dump($email->email);
        //         continue;
        //     }
        //     $unsubscribeKey = md5($subscribing->id.$subscribing->email);
        //     Yii::$app->mailer->compose('subscribe-view-html', compact('posts', 'unsubscribeKey'))
        //         ->setFrom(['no-reply@dynamomania.com' => 'Dynamomania.com'])
        //         ->setTo($subscribing->email)
        //         ->setSubject('Новости Динамо')
        //         ->send();
        // }
        // return $this->redirect(['index']);
    }

    /**
     * Unsubscribe
     * @param $key string
     * @return mixed 
     */
    public function actionUnsubscribe($key)
    {
        $subscribings = Subscribing::find()->all();
        foreach ($subscribings as $subscribing) {
            if(md5($subscribing->id.$subscribing->email) === $key) {
                $subscribing->delete();
                return Json::encode(['success' => true]);
            }
        }
        return Json::encode([
            'success' => false, 
            'msg' => 'Key does not exists',
        ]);
    }

    /**
     * Finds the Subscribing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Subscribing the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subscribing::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
