<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use common\models\Post;
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
        ];
    }

    public function actionIndex()
    {
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

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col3',
            'title' => Yii::t('user','Вход'),
            'columnFirst' => [
                'test_block' => [
                    'view' => '@frontend/views/site/test',
                    'data' => [],
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
            $startDay = date("Y-m-d 00:00:00", strtotime($date));
            $endDay = date("Y-m-d 00:00:00", time() + 60*60*24);
            $query->where(['between', 'created_at', $startDay, $endDay]);
            $query->orderBy(['created_at' => SORT_ASC]);
        } 
        else 
        {
            $query->orderBy(['created_at' => SORT_DESC]);
        }
        $query->limit(15);
        $posts = $query->all();

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Новости',
            'columnFirst' => [
                'news' => [
                    'view' => '@frontend/views/site/news',
                    'data' => compact('posts','date'),
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
    
    public function actionPost($id, $slug) 
    {
        $post = $this->findModel($id);
        $image = $post->getAsset();

        $options = [
            'templateType' => 'col2',
            'title' => 'Новости',
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
            ],
        ];

        if ($post->allow_comment) {
            $comments = $post->getComments();
            $options['columnFirst']['comments'] = [
                'view' => '@frontend/views/blocks/comments_block',
                'data' => compact('comments'),
                'weight' => 5,
            ];
        }
        usort($options['columnFirst'],'self::cmp');

        return $this->render('@frontend/views/site/index', $options);
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
