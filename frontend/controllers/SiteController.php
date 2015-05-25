<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use common\models\Post;
use common\models\Asset;
use common\models\Match;
use common\models\Comment;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;

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
            // 'error' => [
            //     'class' => 'yii\web\ErrorAction',
            // ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url' => 'http://dynamomania.dev/images/store/post_attachments/', // Directory URL address, where files are stored.
                // 'url' => 'http://'.$_SERVER['HTTP_HOST'].'/post_images/', // Directory URL address, where files are stored.
                'path' => '@frontend/web/images/store/post_attachments' // Or absolute path to directory where files are stored.
            ],
        ];
    }

    public function actionIndex()
    {
        $postTable = Post::tableName();
        $assetTable = Asset::tableName();

        $newsPosts = Post::find()
            ->where(['is_public' => 1, 'content_category_id' => Post::CATEGORY_NEWS])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(50)
            ->all();

        $blogPosts = Post::find()
            ->where(['is_public' => 1, 'content_category_id' => Post::CATEGORY_BLOG])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(6)
            ->all();
        
        $top3News = Post::find()
            ->innerJoin($assetTable, "{$assetTable}.assetable_id = {$postTable}.id")
            ->where([
                'is_public' => 1, 
                'is_index' => 1, 
                'content_category_id' => Post::CATEGORY_NEWS,
                "{$assetTable}.assetable_type" => Asset::ASSETABLE_POST,
                "{$assetTable}.thumbnail" => Asset::THUMBNAIL_BIG,
            ])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(3)
            ->all();

        $excludeIds = [];
        foreach ($top3News as $post) {
            $excludeIds[] = $post->id;
        }

        $query = Post::find()
            ->innerJoin($assetTable, "{$assetTable}.assetable_id = {$postTable}.id")
            ->where([
                'is_public' => 1, 
                'is_top' => 1, 
                'content_category_id' => Post::CATEGORY_NEWS,
                "{$assetTable}.assetable_type" => Asset::ASSETABLE_POST,
                "{$assetTable}.thumbnail" => Asset::THUMBNAIL_NEWS,
            ]);
        $top6News = $query->andWhere(['not in', "{$postTable}.id", $excludeIds])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(6)
            ->all();

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col3',
            'title' => 'Главная',
            'columnFirst' => [
                'top3News' => [
                    'view' => '@frontend/views/blocks/main_slider_block',
                    'data' => compact('top3News'),
                ],
                'top6News' => [
                    'view' => '@frontend/views/blocks/main_news_block',
                    'data' => compact('top6News'),
                ],
                'blog_column' => [
                    'view' => '@frontend/views/blocks/blog_block',
                    'data' => ['posts' => $blogPosts],
                ],
            ],
            'columnSecond' => [
                'short_news' => [
                    'view' => '@frontend/views/blocks/news_block',
                    'data' => ['posts' => $newsPosts],
                ],
            ],
            'columnThird' => [
                'test_block' => [
                    'view' => '@frontend/views/site/test',
                    'data' => [],
                ],
            ],
        ]);
    }

    /**
     * @param string $date Searching by date
     * @return mixed Content
     */
    public function actionNews($date = null) 
    {
        $query = Post::find()->where(['is_public' => 1, 'content_category_id' => Post::CATEGORY_NEWS]);
        // check date
        if (strtotime($date) == null) {
            $date = false;
        } else {
            $parsed = date_parse($date);
            if (!checkdate($parsed["month"], $parsed["day"], $parsed["year"])) {
                $date = false;
            }
        }
        if(!empty($date))
        {
            $startDay = date("Y-m-d 00:00:00", 0);
            $endDay = date("Y-m-d 00:00:00", strtotime($date) + 60*60*24);
            $query->where(['between', 'created_at', $startDay, $endDay]);
            $query->orderBy(['created_at' => SORT_DESC]);
        } 
        else 
        {
            $query->orderBy(['created_at' => SORT_DESC]);
        }

        if(!isset($_GET['page']) || $_GET['page'] == 1) {
            Yii::$app->session['news_post_time_last'] = 1;
        }
        $newsDataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $blogPosts = Post::find()
            ->where(['is_public' => 1, 'content_category_id' => Post::CATEGORY_BLOG])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(6)
            ->all();

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Новости',
            'columnFirst' => [
                'news' => [
                    'view' => '@frontend/views/site/news',
                    'data' => compact('date','newsDataProvider'),
                ],
            ],
            'columnSecond' => [
                'test_block' => [
                    'view' => '@frontend/views/site/test',
                    'data' => [],
                ],
                'blog_column' => [
                    'view' => '@frontend/views/blocks/blog_block',
                    'data' => ['posts' => $blogPosts],
                ],
            ],
        ]);
    }
    
    /**
     * @param int $id Post id
     * @param string $slug Post slug
     * @return mixed Content
     */
    public function actionPost($id, $slug) 
    {
        $post = $this->findModel($id);
        $image = $post->getAsset(Asset::THUMBNAIL_CONTENT);

        $blogPosts = Post::find()
            ->where(['is_public' => 1, 'content_category_id' => Post::CATEGORY_BLOG])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(6)
            ->all();

        $options = [
            'templateType' => 'col2',
            'title' => $post->title,
            'columnFirst' => [
                'post' => [
                    'view' => '@frontend/views/site/post',
                    'data' => compact('post','image'),
                    'weight' => 0,
                ],
            ],
            'columnSecond' => [
                'test_block' => [
                    'view' => '@frontend/views/site/test',
                    'data' => [],
                ],
                'blog_column' => [
                    'view' => '@frontend/views/blocks/blog_block',
                    'data' => ['posts' => $blogPosts],
                ],
            ],

        ];

        if ($post->allow_comment) {
            // out all comments
            
            $commentModel = new Comment();
            $commentModel->commentable_id = $post->id;
            $commentModel->commentable_type = Comment::COMMENTABLE_POST;

            // out comments with pagination
            $commentsCount = Comment::find()
                ->where([
                    'commentable_id' => $post->id,
                    'commentable_type' => Comment::COMMENTABLE_POST,
                    'parent_id' => null,
                ])->count();
            $commentsPagination = new Pagination([
                'totalCount' => $commentsCount,
                'pageSize' => 10,
                'pageParam' => 'cpage',
                'pageSizeParam' => 'cpsize',
            ]);

            $initialComments = Comment::find()
                ->where([
                    'commentable_id' => $post->id,
                    'commentable_type' => Comment::COMMENTABLE_POST,
                    'parent_id' => null,
                ])->orderBy(['created_at' => SORT_DESC])
                ->limit($commentsPagination->limit)
                ->offset($commentsPagination->offset)
                ->all();

            $comments = $initialComments;
            while (true) {
                $ids = [];
                foreach ($comments as $comment) {
                    $ids[] = $comment->id;
                }
                $childComments = Comment::find()
                    ->where(['parent_id' => $ids])->orderBy(['created_at' => SORT_ASC])->all();
                if(count($childComments) > 0) {
                    $initialComments = array_merge($initialComments, $childComments);
                    $comments = $childComments;
                } else {
                    break;
                }
            }

            $sortedComments = [];
            foreach ($initialComments as $comment) 
            {
                $index = $comment->parent_id == null ? 0 : $comment->parent_id;
                $sortedComments[$index][] = $comment;
            }
            
            $options['columnFirst']['comments'] = [
                'view' => '@frontend/views/blocks/comments_block',
                'data' => [
                    'comments' => $sortedComments,
                    'commentModel' => $commentModel,
                    'pagination' => $commentsPagination,
                ],
                'weight' => 5,
            ];
        }
        usort($options['columnFirst'],'self::cmp');

        return $this->render('@frontend/views/site/index', $options);
    }

    /**
     * Adds a new comment
     * If adding is successful, the browser will be redirected to the 'previ' page.
     * 
     * @return mixed
     */
    public function actionCommentAdd() 
    {
        $model = new Comment();
        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->id;
            if($model->save()) {
                $referrer .= '#comment-'.$model->id;
            }
        }

        return $this->redirect($referrer);
    }
    
    /**
     * Adds a new comment
     * If adding is successful, the browser will be redirected to the 'previ' page.
     * 
     * @return mixed
     */
    public function actionMatches() 
    {
        $query = Match::find()->where(['is_visible' => 1]);
        $query->orderBy(['date' => SORT_DESC]);
        
        $matchDataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
        
        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Матчи',
            'columnFirst' => [
                'matches' => [
                    'view' => '@frontend/views/site/matches',
                    'data' => compact('matchDataProvider'),
                ],
            ],
            'columnSecond' => [
                'test_block' => [
                    'view' => '@frontend/views/site/test',
                    'data' => [],
                ],                
            ],
        ]);
    }
    
    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Comparing a weight of blocks in columns
     * @param array $a
     * @param array $b
     * @return int Result of comparing
     */
    private static function cmp($a, $b)
    {
        if ($a['weight'] == $b['weight']) {
            return 0;
        }
        return ($a['weight'] < $b['weight']) ? -1 : 1;
    }

}
