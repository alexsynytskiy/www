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
use common\models\TransferType;
use common\models\Transfer;
use common\models\Tournament;
use common\models\Forward;
use common\models\Championship;
use common\models\CommentForm;
use common\models\SiteBlock;
use common\models\MatchEvent;
use common\models\Composition;
use common\models\Question;
use common\models\Claim;
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

    /**
     * Main page
     * @return mixed Content
     */
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
                'questionBlock' => SiteBlock::getQuestionBlock(),
                'tournament' => SiteBlock::getTournamentTable(),
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
        // $query->andWhere(['<', 'created_at', date("Y-m-d H:00:00")]);
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
            $query->andFilterWhere(['between', 'created_at', $startDay, $endDay]);
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
            ->where(['window' => Season::WINDOW_WINTER])
            ->andWhere(['>', 'id', 42])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        if (isset($_GET['season'])) {
            $activeSeason = $_GET['season'];
        }
        else {
            $activeSeasonObj = array_values($seasons)[0];
            $activeSeason = $activeSeasonObj->id;
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
                'short_news' => SiteBlock::getShortNews(),
            ],
        ]);
    }

    /**
     * Transfers page
     * 
     * @return mixed
     */
    public function actionTransfers() 
    {
        // transfer type select
        $transferTypes = TransferType::find()->orderBy(['id' => SORT_DESC])->all();
        $activeTransferType = 'all-types';
        if(isset($_GET['transfer-type'])) {
            foreach ($transferTypes as $transferType) {
                if($_GET['transfer-type'] == $transferType->id){
                    $activeTransferType = $_GET['transfer-type'];
                } 
            }
        }
        $transferTypesData = [
            'all-types' => ['value' => 'all-types', 'text' => 'Все трансферы', 'active' => false],
        ];
        foreach ($transferTypes as $transferType) {
            $transferTypesData[$transferType->id] = [
                'value' => $transferType->id,
                'text' => $transferType->name,
                'active' => false,
            ];
        }
        $transferTypesData[$activeTransferType]['active'] = true;
        foreach ($transferTypesData as $key => $transferType) {
            $transferTypesData[$key] = (object) $transferType;
        }

        // season select
        $seasons = Season::find()
            ->where(['>', 'id', 42])
            ->orderBy(['id' => SORT_DESC])
            ->all();
        
        $firstSeasonObj = array_values($seasons)[0];
        $firstSeasonId = $firstSeasonObj->id;
        $activeSeason = $firstSeasonId;
        if(isset($_GET['season'])) {
            foreach ($seasons as $season) {
                if($_GET['season'] == $season->id){
                    $activeSeason = $_GET['season'];
                } 
            }
        }
        $seasonsData = [];
        foreach ($seasons as $season) {
            $seasonName = $season->name;
            if($season->window == $season::WINDOW_WINTER) {
                $seasonName .= ' Зимнее окно';
            } else {
                $seasonName .= ' Летнее окно';
            }
            $seasonsData[$season->id] = [
                'value' => $season->id,
                'text' => $seasonName,
                'active' => false,
            ];
        }
        $seasonsData[$activeSeason]['active'] = true;
        foreach ($seasonsData as $key => $season) {
            $seasonsData[$key] = (object) $season;
        }

        // transfers tables
        $transferQuery = Transfer::find()
            ->where(['season_id' => $activeSeason])
            ->orderBy(['created_at' => SORT_ASC]);
        if($activeTransferType != 'all-types') {
            $transferQuery->andWhere(['transfer_type_id' => $activeTransferType]);
        }
        $transfers = $transferQuery->all();


        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Трансферы',
            'columnFirst' => [
                'transfers' => [
                    'view' => '@frontend/views/transfers/transfers',
                    'data' => compact('transferTypesData', 'seasonsData', 'transfers'),
                ],
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(),
            ],
        ]);
    }

    /**
     * Transfers page
     * 
     * @param int $id Transfer id
     * @return mixed
     */
    public function actionTransfer($id) 
    {
        $transfer = Transfer::findOne($id);
        
        if(!isset($transfer)) {
            throw new NotFoundHttpException('Страница не найдена.');
        }

        $commentForm = new CommentForm();
        $commentForm->commentable_id = $transfer->id;
        $commentForm->commentable_type = Comment::COMMENTABLE_TRANSFER;

        // out comments with pagination
        $commentsCount = Comment::find()
            ->where([
                'commentable_id' => $transfer->id,
                'commentable_type' => Comment::COMMENTABLE_TRANSFER,
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
                'commentable_id' => $transfer->id,
                'commentable_type' => Comment::COMMENTABLE_TRANSFER,
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
        $comments = $sortedComments;

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Трансферы',
            'columnFirst' => [
                'transfers' => [
                    'view' => '@frontend/views/transfers/transfer_single',
                    'data' => compact('transfer'),
                ],
                'comments' => [
                    'view' => '@frontend/views/blocks/comments_block',
                    'data' => [
                        'comments' => $sortedComments,
                        'commentForm' => $commentForm,
                        'pagination' => $commentsPagination,
                    ],
                ],
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(),
            ],
        ]);
    }

    /**
     * Tournament page
     * 
     * @return mixed
     */
    public function actionTournament() 
    {
        $tournamentTable = Tournament::tableName();
        $championshipTable = Championship::tableName();
        $seasonTable = Season::tableName();

        // championship type select
        $championships = Championship::find()
            ->innerJoin($tournamentTable, "{$tournamentTable}.championship_id = {$championshipTable}.id")
            ->orderBy(['id' => SORT_DESC])
            ->all();

        $firstChampionshipObj = array_values($championships)[0];
        $firstChampionshipId = $firstChampionshipObj->id;
        $activeChampionship = $firstChampionshipId;
        if(isset($_GET['championship'])) {
            foreach ($championships as $championship) {
                if($_GET['championship'] == $championship->id){
                    $activeChampionship = $_GET['championship'];
                } 
            }
        }
        $championshipsData = [];
        foreach ($championships as $championship) {
            $championshipsData[$championship->id] = [
                'value' => $championship->id,
                'text' => $championship->name,
                'active' => false,
            ];
        }
        $championshipsData[$activeChampionship]['active'] = true;
        foreach ($championshipsData as $key => $championship) {
            $championshipsData[$key] = (object) $championship;
        }

        // season select
        $seasons = Season::find()
            ->innerJoin($tournamentTable, "{$tournamentTable}.season_id = {$seasonTable}.id")
            ->orderBy(['id' => SORT_DESC])
            ->all();
        
        $firstSeasonObj = array_values($seasons)[0];
        $firstSeasonId = $firstSeasonObj->id;
        $activeSeason = $firstSeasonId;
        if(isset($_GET['season'])) {
            foreach ($seasons as $season) {
                if($_GET['season'] == $season->id){
                    $activeSeason = $_GET['season'];
                } 
            }
        }
        $seasonsData = [];
        foreach ($seasons as $season) {
            $seasonName = $season->name;
            $seasonsData[$season->id] = [
                'value' => $season->id,
                'text' => $seasonName,
                'active' => false,
            ];
        }
        $seasonsData[$activeSeason]['active'] = true;
        foreach ($seasonsData as $key => $season) {
            $seasonsData[$key] = (object) $season;
        }

        $tournamentData = Tournament::find()
            ->where([
                'season_id' => $activeSeason,
                'championship_id' => $activeChampionship,
            ])->orderBy(['points' => SORT_DESC])
            ->all();

        $tournamentData = Tournament::sort($tournamentData);

        $forwards = Forward::find()
            ->orderBy([
                'goals' => SORT_DESC,
                'penalty' => SORT_DESC,
            ])->all();

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Турнирная таблица',
            'columnFirst' => [
                'tournament' => [
                    'view' => '@frontend/views/tournament/tournament_full',
                    'data' => compact('tournamentData', 'championshipsData', 'seasonsData'),
                ],
                'forwards' => [
                    'view' => '@frontend/views/tournament/forwards',
                    'data' => compact('forwards'),
                ],
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(),
            ],
        ]);
    }

    /**
     * Transfers page
     * 
     * @param int $id Match id
     * @return mixed
     */
    public function actionTranslation($id) 
    {
        $match = Match::findOne($id);
        
        if(!isset($match)) {
            throw new NotFoundHttpException('Страница не найдена.');
        }

        $matchEvents = MatchEvent::find()
            ->where(['match_id' => $match->id])
            ->all();

        $teamPlayers = Composition::find()
            ->where([
                'match_id' => $match->id, 
            ])
            ->all();

        $teamHomePlayers = [];
        $teamGuestPlayers = [];
        foreach ($teamPlayers as $player) {
            if($player->command_id == $match->command_home_id) {
                $teamHomePlayers[] = $player;
            } else {
                $teamGuestPlayers[] = $player;
            }
        }

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Трансляция',
            'columnFirst' => [
                'translation' => [
                    'view' => '@frontend/views/translation/index',
                    'data' => compact('match', 'matchEvents', 'teamHomePlayers', 'teamGuestPlayers'),
                ],
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(),
            ],
        ]);
    }

    /**
     * Inquirers page
     * 
     * @return mixed
     */
    public function actionInquirers() 
    {
        $query = Question::find()
            ->where([
                'parent_id' => null,
            ])->orderBy(['created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Все опросы',
            'columnFirst' => [
                'inquirers' => [
                    'view' => '@frontend/views/site/inquirers',
                    'data' => compact('dataProvider'),
                ],
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(),
            ],
        ]);
    }

    /**
     * Claim page
     * 
     * @param int $id Comment id
     * @return mixed
     */
    public function actionComplain($id) 
    {
        $comment = Comment::findOne($id);
        
        if(!isset($comment)) {
            throw new NotFoundHttpException('Страница не найдена.');
        }

        if(Yii::$app->user->isGuest) {
            throw new BadRequestHttpException('Для отправки жалобы авторизируйтесь.');
        }

        $claim = Claim::find()
            ->where([
                'comment_id' => $comment->id,
                'user_id' => Yii::$app->user->id,
            ])
            ->one();

        if(!isset($claim->id)) {
            $claim = new Claim();
            $claim->comment_id = $comment->id;
            $claim->comment_author = $comment->user->id;
            $claim->user_id = Yii::$app->user->id;
        }

        if($claim->load(Yii::$app->request->post())){
            $claim->save();
        }

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Жалоба',
            'columnFirst' => [
                'claim' => [
                    'view' => '@frontend/views/forms/complain_form',
                    'data' => compact('comment', 'claim'),
                ],
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(),
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
            throw new NotFoundHttpException('Страница не найдена.');
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
