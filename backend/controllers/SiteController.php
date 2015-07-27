<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\HttpException;

use common\models\Post;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'image-upload'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'subscribe-send'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url' => 'http://'.$_SERVER['HTTP_HOST'].'/images/store/post_attachments/', // Directory URL address, where files are stored.
                'path' => '@frontend/web/images/store/post_attachments' // Or absolute path to directory where files are stored.
            ],
        ];
    }

    public function actionIndex()
    {
        if (!empty(Yii::$app->user) && !Yii::$app->user->can("admin")) {
            throw new \yii\web\ForbiddenHttpException('Вы не можете выполнить это действие.');
        }
        
        $connection = Yii::$app->db;

        $countSqlNews = 'SELECT DATE(created_at) AS d, count(id) AS c
                    FROM posts
                    WHERE created_at > DATE_SUB(NOW(), INTERVAL 30 day) AND content_category_id = 1
                    GROUP BY DATE(created_at)';

        $countSqlComments = 'SELECT DATE(created_at) AS d, count(id) AS c
                    FROM comments
                    WHERE created_at > DATE_SUB(NOW(), INTERVAL 30 day)
                    GROUP BY DATE(created_at)';

        $countSqlNewUsers = 'SELECT DATE(create_time) AS d, count(id) AS c
                    FROM users
                    WHERE create_time > DATE_SUB(NOW(), INTERVAL 30 day)
                    GROUP BY DATE(create_time)';

        $cmd = $connection->createCommand($countSqlNews);
        $newsCount = $cmd->queryAll();
        
        $cmd = $connection->createCommand($countSqlComments);
        $commentsCount = $cmd->queryAll();

        $cmd = $connection->createCommand($countSqlNewUsers);
        $newUsersCount = $cmd->queryAll();

        $daysNewsCount = [];        
        $daysNewsDates = [];
        $daysCommentsCount = [];
        $daysCommentsDates = [];
        $daysNewUsersCount = [];
        $daysNewUsersDates = []; 

        foreach ($newsCount as $count) {
            $daysNewsCount[] = $count['c'];
            $daysNewsDates[] = '"'.$count['d'].'"';
        }

        foreach ($commentsCount as $count) {
            $daysCommentsCount[] = $count['c'];
            $daysCommentsDates[] = '"'.$count['d'].'"';
        }

        foreach ($newUsersCount as $count) {
            $daysNewUsersCount[] = $count['c'];
            $daysNewUsersDates[] = '"'.$count['d'].'"';
        }        

        $daysNewsDatesString = implode(",", $daysNewsDates);
        $daysNewsCountString = implode(",", $daysNewsCount);

        $daysCommentsDatesString = implode(",", $daysCommentsDates);
        $daysCommentsCountString = implode(",", $daysCommentsCount);

        $daysNewUsersDatesString = implode(",", $daysNewUsersDates);
        $daysNewUsersCountString = implode(",", $daysNewUsersCount);

        return $this->render('index',  compact('daysNewsCountString', 
                                               'daysNewsDatesString',
                                               'daysCommentsDatesString', 
                                               'daysCommentsCountString',
                                               'daysNewUsersDatesString', 
                                               'daysNewUsersCountString'));
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Sending emails to subscribers
     * @return mixed Content
     */
    // public function actionSubscribeSend()
    // {
    //     $username = 'olgert';
    //     $currentDayTime = strtotime(date('d.m.Y', time()));
    //     $currentDay = date("Y-m-d H:i:s", $currentDayTime);
    //     $importantPosts = Post::find()
    //         ->where([
    //             'is_public' => 1, 
    //             'is_index' => 1,
    //         ])
    //         ->andWhere(['>', 'created_at', $currentDay])
    //         ->orderBy(['created_at' => SORT_DESC])
    //         ->limit(3)->all();
    //     $ids = [];
    //     foreach ($importantPosts as $post) {
    //         $ids[] = $post->id;
    //     }

    //     $maxCommentsPosts = Post::find()
    //         ->where([
    //             'is_public' => 1, 
    //         ])
    //         ->andWhere(['>', 'created_at', $currentDay])
    //         ->andWhere(['not in', "id", $ids])
    //         ->orderBy([
    //             'comments_count' => SORT_DESC,
    //         ])
    //         ->limit(3)->all();
    //     $posts = array_merge($importantPosts ,$maxCommentsPosts);
    //     Yii::$app->mailer->compose('subscribe-view-html', compact('username', 'posts'))
    //         ->setFrom(['no-reply@dynamomania.com' => 'Dynamomania.com'])
    //         ->setTo('olgert.vaskevich@gmail.com')
    //         ->setSubject('Новости Динамо')
    //         ->send();
    //     return '';
    // }
}
