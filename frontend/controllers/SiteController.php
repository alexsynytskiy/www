<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use common\models\Post;
use common\models\Asset;
use common\models\Match;
use common\models\Team;
use common\models\Comment;
use common\models\Season;
use common\models\Championship;
use common\models\CommentForm;
use common\models\SiteBlock;
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
use yii\helpers\Json;

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
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url' => 'http://dynamomania.dev/images/store/post_attachments/', // Directory URL address, where files are stored.
                'path' => '@frontend/web/images/store/post_attachments' // Or absolute path to directory where files are stored.
            ],
        ];
    }

    public function actionIndex()
    {
        
        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col3',
            'title' => 'Главная',
            'columnFirst' => [
                'top3News' => SiteBlock::getTop3News(),
                'top6News' => SiteBlock::getTop6News(),
                'blog_column' => SiteBlock::getBlogPosts(),
            ],
            'columnSecond' => [
                'slider_matches' => SiteBlock::getMatchesSlider(),
                'short_news' => SiteBlock::getShortNews(),
            ],
            'columnThird' => [
                'reviewNews' => SiteBlock::getPhotoVideoNews(),
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
                'blog_column' => SiteBlock::getBlogPosts(),
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
                'blog_column' => SiteBlock::getBlogPosts(),
            ],

        ];

        if ($post->allow_comment) {
            // out all comments
            
            $commentForm = new CommentForm();
            $commentForm->commentable_id = $post->id;
            $commentForm->commentable_type = Comment::COMMENTABLE_POST;

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
                    'commentForm' => $commentForm,
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
        $model = new CommentForm();

        $out = ['success' => false];
        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->id;
            if($model->save()) {
                $out = [
                    'success' => true,
                    'newID' => $model->id,
                ];
            }
        }

        echo Json::encode($out);
    }
    
    /**
     * Adds a new comment
     * If adding is successful, the browser will be redirected to the 'previ' page.
     * 
     * @return mixed
     */
    public function actionMatches() 
    {
        //select teams of interest for page matches sort
        $selectTeamsOI = [
            Team::TEAM_DK_FIRST_FULL_NAME => Team::findOne(Team::TEAM_DK_FIRST_FULL_NAME),
            Team::TEAM_DK_M => Team::findOne(Team::TEAM_DK_M),
            Team::TEAM_DK2 => Team::findOne(Team::TEAM_DK2),
            Team::TEAM_U19 => Team::findOne(Team::TEAM_U19),
            Team::TEAM_UKRAINE => Team::findOne(Team::TEAM_UKRAINE),
        ];        

        if (isset($_GET['team'])) {
            $activeTeam = $_GET['team'];
        }
        else {
            $activeTeam = Team::TEAM_DK_FIRST_FULL_NAME;
        }

        //select seasons
        $seasons = Season::find()
        ->where(['>', 'id', 42])
        ->orderBy(['id' => SORT_DESC])
        ->all();

        foreach ($seasons as $key => $season) {
           if (strpos($season->name, '/') === false) {
               unset($seasons[$key]);
           }
        }

        if (isset($_GET['season'])) {
            $activeSeason = $_GET['season'];
        }
        else {
            $activeSeason = $seasons[0]->id;
        }

        //select tournaments
        $tableTournament = Championship::tableName();
        $tableMatch = Match::tableName();

        $tournaments = Championship::find()
        ->innerJoin($tableMatch, "{$tableMatch}.championship_id = {$tableTournament}.id")
        ->where(['is_visible' => 1])
        ->andWhere(['or', ["{$tableMatch}.command_home_id" => $activeTeam], ["{$tableMatch}.command_guest_id" => $activeTeam]])
        ->orderBy(['id' => SORT_DESC])
        ->all();

        $query = NULL;     

        if (isset($_GET['championship'])) {
            $activeTournament = $_GET['championship'];
            if ($_GET['championship'] == 'all-tournaments') {
                $activeTournament = NULL;
            }
        }
        else {
            $activeTournament = NULL;
        }

        $query = Match::find()
        ->where(['is_visible' => 1, 'season_id' => $activeSeason])
        ->andWhere(['or', ['command_home_id' => $activeTeam], ['command_guest_id' => $activeTeam]]);

        if (isset($activeTournament)) {
            $query->andWhere(['championship_id' => $activeTournament]);
        }

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
                    'data' => compact('matchDataProvider', 
                                      'selectTeamsOI', 
                                      'activeTeam', 
                                      'seasons', 
                                      'activeSeason', 
                                      'tournaments', 
                                      'activeTournament'),
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
