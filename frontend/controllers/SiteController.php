<?php
namespace frontend\controllers;

use Yii;
use common\models\Post;
use common\models\Tag;
use common\models\Tagging;
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
use common\models\Contract;
use common\models\MainInfo;
use frontend\models\ContactForm;
use common\models\Source;


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
use yii\web\UploadedFile;

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
                'subscribing' => SiteBlock::getSubscribingForm(),
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
     * @param string $t Tag name slug
     * @param string $q Search words
     * @return mixed Content
     */
    public function actionSearch($t = false, $q = false) 
    {
        $t = str_replace('-+-', '-#%#-', $t);
        $t = str_replace('+', ' ', $t);
        $t = str_replace('-#%#-', '+', $t);
        $postTable = Post::tableName();
        $query = Post::find()->where([
            'is_public' => 1, 
            'content_category_id' => Post::CATEGORY_BLOG,
        ]);
        $view = '@frontend/views/site/news';

        if(isset($t) && trim($t) != '') {
            $taggingTable = Tagging::tableName();
            $tagTable = Tag::tableName();
            $query->innerJoin($taggingTable, "{$postTable}.id = {$taggingTable}.taggable_id");
            $query->innerJoin($tagTable, "{$taggingTable}.tag_id = {$tagTable}.id");
            $query->andWhere([
                "{$taggingTable}.taggable_type" => Tagging::TAGGABLE_POST,
                "{$tagTable}.name" => $t,
            ]);
            $view = '@frontend/views/search/search_posts';
        } elseif(isset($q) && trim($q) != '') {
            $search = addslashes($q);
            $query->andWhere("MATCH (content) AGAINST ('$search')");

            // Sphinx
            // $query = new \yii\sphinx\Query;
            // $query->from('post_index')
            //     ->match($q)
            //     ->limit(1000);

            // $sphinxIds = $query->all();
            // $ids = [];
            // foreach ($sphinxIds as $data) {
            //     $ids[] = $data['id'];
            // }
            // $query = Post::find()
            //     ->where(['id' => $ids]);
        }
        $query->orderBy(["$postTable.created_at" => SORT_DESC]);

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
            'title' => 'Поиск',
            'columnFirst' => [
                'news' => [
                    'view' => '@frontend/views/search/search_posts',
                    'data' => compact('newsDataProvider'),
                ],
            ],
            'columnSecond' => [
                'blog_column' => SiteBlock::getBlogPosts(),
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
                'calendar' => [
                    'view' => '@frontend/views/site/news_calendar',
                    'data' => compact('date'),
                ],
                'news' => [
                    'view' => '@frontend/views/site/news',
                    'data' => compact('newsDataProvider'),
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
        $post = Post::findOne($id);
        if (!isset($post)){
            throw new NotFoundHttpException('Страница не найдена.');
        }
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
            
            $options['columnFirst']['comments'] = Comment::getCommentsBlock($post->id, Comment::COMMENTABLE_POST);
            $options['columnFirst']['comments']['weight'] = 5;
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

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Трансферы',
            'columnFirst' => [
                'transfers' => [
                    'view' => '@frontend/views/transfers/transfer_single',
                    'data' => compact('transfer'),
                ],
                'comments' => Comment::getCommentsBlock($transfer->id, Comment::COMMENTABLE_TRANSFER),
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
     * Translation page
     * 
     * @param int $id Match id
     * @return mixed
     */
    public function actionMatchTranslation($id) 
    {
        $match = Match::findOne($id);
        
        if(!isset($match)) {
            throw new NotFoundHttpException('Страница не найдена.');
        }

        $title = "Онлайн трансляция матча ".$match->teamHome->name." - ".$match->teamGuest->name;

        $matchEvents = MatchEvent::find()
            ->where(['match_id' => $match->id])
            ->all();

        $teamPlayers = Composition::getSquadSort($match->id);

        $teamHomePlayers = array();
        $teamGuestPlayers = array();
        foreach ($teamPlayers as $player) {
            if($player->command_id == $match->command_home_id) {
                array_push($teamHomePlayers, $player);
            } else {
                array_push($teamGuestPlayers, $player);
            }
        }

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => $title,
            'columnFirst' => [
                'menu' => [
                    'view' => '@frontend/views/translation/menu',
                    'data' => compact('match'),
                ],
                'translation' => [
                    'view' => '@frontend/views/translation/index',
                    'data' => compact('match', 'matchEvents', 'teamHomePlayers', 'teamGuestPlayers'),
                ],
                'comments' => Comment::getCommentsBlock($match->id, Comment::COMMENTABLE_MATCH),
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(),
            ],
        ]);
    }

    /**
     * Translation page
     * 
     * @return mixed
     */
    public function actionMatchProtocol($id) 
    {
        $match = Match::findOne($id);
        
        if(!isset($match)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $title = "Протокол матча ".$match->teamHome->name." - ".$match->teamGuest->name;

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
            'title' => $title,
            'columnFirst' => [
                'menu' => [
                    'view' => '@frontend/views/translation/menu',
                    'data' => compact('match'),
                ],
                'protocol' => [
                    'view' => '@frontend/views/translation/protocol',
                    'data' => compact('match', 'matchEvents', 'teamHomePlayers', 'teamGuestPlayers'),
                ],
                'comments' => Comment::getCommentsBlock($match->id, Comment::COMMENTABLE_MATCH),
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(),
            ],
        ]);
    }

    /**
     * Match news page
     * 
     * @return mixed
     */
    public function actionMatchNews($id) 
    {
        $match = Match::findOne($id);
        
        if(!isset($match)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $title = "Материалы по матчу ".$match->teamHome->name." - ".$match->teamGuest->name;
        
        $postTable = Post::tableName();
        $taggingTable = Tagging::tableName();
        $tagTable = Tag::tableName();

        $startDate = $match->date;
        $endDate = date('Y-m-d H:i:s', strtotime($match->date) + 60*60*24*7);
        $teamHomeName = $match->teamHome->name;
        $teamHomeName = str_replace('"', '', $teamHomeName);
        $teamHomeTemp = explode(' ', $teamHomeName);
        $teamHomeName = array_shift($teamHomeTemp);
        $teamGuestName = $match->teamGuest->name;
        $teamGuestName = str_replace('"', '', $teamGuestName);
        $teamGuestTemp = explode(' ', $teamGuestName);
        $teamGuestName = array_shift($teamGuestTemp);

        $query = Post::find();
        $query->select(["{$postTable}.*", 'co' => 'COUNT(*)']);
        $query->innerJoin($taggingTable, "{$postTable}.id = {$taggingTable}.taggable_id");
        $query->innerJoin(['tags' => $tagTable], "{$taggingTable}.tag_id = tags.id");
        $query->where([
                'is_public' => 1,
                "{$taggingTable}.taggable_type" => Tagging::TAGGABLE_POST,
            ]);
        $query->andWhere(['between', 'created_at', $startDate, $endDate]);
        $query->andWhere([
            'or', 
            ['like', "tags.name", $teamHomeName],
            ['like', "tags.name", $teamGuestName],
        ]);
        $query->orderBy(['created_at' => SORT_ASC]);
        $query->groupBy("{$postTable}.id");
        $query->having(['>', 'co', 1]);

        if(!isset($_GET['page']) || $_GET['page'] == 1) {
            Yii::$app->session['news_post_time_last'] = 1;
        }
        $postsDataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => $title,
            'columnFirst' => [
                'menu' => [
                    'view' => '@frontend/views/translation/menu',
                    'data' => compact('match'),
                ],
                'translation' => [
                    'view' => '@frontend/views/site/news',
                    'data' => [
                        'newsDataProvider' => $postsDataProvider,
                    ],
                ],
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(),
            ],
        ]);
    }

    /**
     * Match report page
     * 
     * @return mixed
     */
    public function actionMatchReport($id) 
    {
        $match = Match::findOne($id);
        
        if(!isset($match)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $postTable = Post::tableName();
        $taggingTable = Tagging::tableName();
        $tagTable = Tag::tableName();

        $startDate = $match->date;
        $teamHomeName = $match->teamHome->name;
        $teamHomeName = str_replace('"', '', $teamHomeName);
        $teamHomeTemp = explode(' ', $teamHomeName);
        $teamHomeName = array_shift($teamHomeTemp);
        $teamGuestName = $match->teamGuest->name;
        $teamGuestName = str_replace('"', '', $teamGuestName);
        $teamGuestTemp = explode(' ', $teamGuestName);
        $teamGuestName = array_shift($teamGuestTemp);

        $query = Post::find();
        $query->select(["{$postTable}.*", 'co' => 'COUNT(*)']);
        $query->innerJoin($taggingTable, "{$postTable}.id = {$taggingTable}.taggable_id");
        $query->innerJoin(['tags' => $tagTable], "{$taggingTable}.tag_id = tags.id");
        $query->where([
                'is_public' => 1,
                "{$taggingTable}.taggable_type" => Tagging::TAGGABLE_POST,
            ]);
        $query->andWhere(['>', 'created_at', $startDate]);
        $query->andWhere([
            'or', 
            ['like', "tags.name", $teamHomeName],
            ['like', "tags.name", $teamGuestName],
        ]);
        $query->orderBy(['created_at' => SORT_ASC]);
        $query->groupBy("{$postTable}.id");
        $query->having(['>', 'co', 1]);

        $post = $query->one();
        
        $options = [
            'templateType' => 'col2',
            'title' => 'Отчет по матчу: '.$match->teamHome->name." - ".$match->teamGuest->name,
            'columnFirst' => [
                'menu' => [
                    'view' => '@frontend/views/translation/menu',
                    'data' => compact('match'),
                ],
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(),
            ],
        ];

        if(isset($post)) {
            $image = $post->getAsset(Asset::THUMBNAIL_CONTENT);
            
            $options['columnFirst']['post'] = [
                'view' => '@frontend/views/site/post',
                'data' => compact('post','image'),
                'weight' => 3,
            ];
            if ($post->allow_comment) {
                $options['columnFirst']['comments'] = Comment::getCommentsBlock($post->id, Comment::COMMENTABLE_POST);
                $options['columnFirst']['comments']['weight'] = 5;
            }
        } else {
            $options['columnFirst']['post'] = [
                'view' => '@frontend/views/site/empty',
                'data' => [],
                'weight' => 3,
            ];
        }
        usort($options['columnFirst'],'self::cmp');

        return $this->render('@frontend/views/site/index', $options);
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
     * Team full info page
     * @param $id int Team id
     * @param $tab string Team id
     * @return mixed
     */
    public function actionTeam($tab, $id = false) 
    {
        if($id === false) $id = Team::TEAM_DK_FIRST_FULL_NAME;
        $team = Team::findOne($id);
        $tabs = ['info', 'composition', 'achievements', 'record-holders'];
        if(!isset($team) || !in_array($tab, $tabs)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if($tab == 'composition') {
            $availableTeams = [
                Team::TEAM_DK_FIRST_FULL_NAME => Team::findOne(Team::TEAM_DK_FIRST_FULL_NAME),
                Team::TEAM_DK_M => Team::findOne(Team::TEAM_DK_M),
                Team::TEAM_DK2 => Team::findOne(Team::TEAM_DK2),
                Team::TEAM_U19 => Team::findOne(Team::TEAM_U19),
            ];   

            $seasonTable = Season::tableName();
            $contractTable = Contract::tableName();
            $availableSeasons = Season::find()
                ->innerJoin('contracts', "$seasonTable.id = $contractTable.season_id")
                ->where(['window' => Season::WINDOW_WINTER])
                ->andWhere(["$contractTable.command_id" => $id])
                ->orderBy(["$seasonTable.id" => SORT_DESC])
                ->all();
            $availableSeasonsIds = [];
            foreach ($availableSeasons as $season) {
                $availableSeasonsIds[] = $season->id;
            }
            if (isset($_GET['season']) && in_array($_GET['season'], $availableSeasonsIds)) {
                $activeSeason = $_GET['season'];
            }
            else {
                $activeSeason = $availableSeasonsIds[0];
            }

            $composition = Contract::find()
                ->where([
                    'season_id' => $activeSeason,   
                    'command_id' => $team->id,   
                ])
                ->orderBy(['amplua_id' => SORT_ASC])
                ->all();

            $data = [
                'teamModel' => $team,
                'availableSeasons' => $availableSeasons,
                'activeSeason' => $activeSeason,
                'availableTeams' => $availableTeams,
                'activeTeam' => $team->id,
                'composition' => $composition,
            ];
        } else {
            $information = MainInfo::find()->all();
            $info = [];
            foreach ($information as $data) {
                $info[$data->name] = $data;
            }
            $data = compact('team', 'info');
        }

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => $team->name,
            'columnFirst' => [
                'navbar' => [
                    'view' => '@frontend/views/team/menu',
                    'data' => compact('team', 'tab'),
                ],
                'content' => [
                    'view' => '@frontend/views/team/tab-'.$tab,
                    'data' => $data,
                ],
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(),
            ],
        ]);
    }

    /**
     * Add post page
     * 
     * @return mixed
     */
    public function actionPostAdd() 
    {
        $model = new Post();
        $model->tags = [];
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->allow_comment = 1;
            $model->is_public = 1;
            $model->comments_count = 0;
            $model->content_category_id = Post::CATEGORY_BLOG;
            $model->user_id = Yii::$app->user->id;

            // Set slug
            $model->slug = $model->genSlug($model->title);

            // Save source
            // $source = new Source;
            // $source->name = $model->source_title;
            // $source->url = $model->source_url;
            // if(!$source->modelExist()) {
            //     $source->save();
            // }

            // Save the model to have a record number
            if($model->save())
            {
                // Adding new tags
                if(is_array($model->tags))
                {
                    foreach ($model->tags as $id) {
                        $model->addTag($id);
                    }
                }

                $cached_tag_list = [];
                $newTags = $model->getTags();
                foreach ($newTags as $newTag) {
                    $cached_tag_list[] = $newTag->name;
                }
                $model->cached_tag_list = implode(', ', $cached_tag_list);

                // Set image
                $model->image = UploadedFile::getInstance($model, 'image');
                if($model->image)
                {
                    $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_POST);

                    foreach ($thumbnails as $thumbnail) {
                        $asset = new Asset();
                        $asset->thumbnail = $thumbnail;
                        $asset->assetable_type = Asset::ASSETABLE_POST;
                        $asset->assetable_id = $model->id;
                        $asset->uploadedFile = $model->image;
                        $asset->saveAsset();
                    }

                    $asset = new Asset();
                    $asset->assetable_type = Asset::ASSETABLE_POST;
                    $asset->assetable_id = $model->id;
                    $asset->uploadedFile = $model->image;
                    $asset->saveAsset();
                }
                $model->save(false);
                return $this->redirect($model->getUrl());
            }
        } 
        $title = 'Добавить запись в блог';
        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => $title,
            'columnFirst' => [
                'blog_form' => [
                    'view' => '@frontend/views/forms/blog_form',
                    'data' => compact('model', 'tags', 'title'),
                ],
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(),
            ],
        ]);
    }

    /**
     * Edit post page
     * 
     * @param $id int Post id
     * @return mixed
     */
    public function actionPostEdit($id) 
    {
        $model = Post::findOne($id);
        if (!isset($model)){
            throw new NotFoundHttpException('Страница не найдена.');
        }
        if($model->content_category_id != Post::CATEGORY_BLOG || 
            $model->user_id != Yii::$app->user->id) {
            throw new BadRequestHttpException("Ошибка доступа");
        }

        $image = $model->getAsset();
        $tags = $model->getTags();
        $assets = $model->getAssets();
        $model->tags = [];
        foreach ($tags as $tag) {
            $model->tags[] = $tag->id;
        }

        $model->title = html_entity_decode($model->title);
        $model->content = html_entity_decode($model->content);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            // Set slug
            $model->slug = $model->genSlug($model->title);

            // Set image
            $model->image = UploadedFile::getInstance($model, 'image');
            if($model->image)
            {
                $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_POST);
                $saveOrigin = false;
                foreach ($assets as $asset)
                {
                    if($asset->thumbnail && in_array($asset->thumbnail, $thumbnails))
                    {
                        $asset->uploadedFile = $model->image;
                        $asset->saveAsset();
                        $thumbnails = array_diff($thumbnails, [$asset->thumbnail]);
                    }
                    // Save original image
                    elseif (empty($asset->thumbnail))
                    {
                        $saveOrigin = true;
                        $asset->uploadedFile = $model->image;
                        $asset->saveAsset();
                    }
                }

                foreach ($thumbnails as $thumbnail) {
                    $asset = new Asset();
                    $asset->thumbnail = $thumbnail;
                    $asset->assetable_type = Asset::ASSETABLE_POST;
                    $asset->assetable_id = $model->id;
                    $asset->uploadedFile = $model->image;
                    $asset->saveAsset();
                }

                if(!$saveOrigin)
                {
                    $asset = new Asset();
                    $asset->assetable_type = Asset::ASSETABLE_POST;
                    $asset->assetable_id = $model->id;
                    $asset->uploadedFile = $model->image;
                    $asset->saveAsset();
                }
            }

            // Save source
            // $source = new Source;
            // $source->name = strip_tags($model->source_title);
            // $source->url = strip_tags($model->source_url);
            // if(!$source->modelExist()) {
            //     $source->save();
            // }

            $existingTags = [];
            // Remove tags
            foreach ($tags as $tag) {
                if(!is_array($model->tags) || !in_array($tag->id, $model->tags)) {
                    $model->removeTag($tag->id);
                } else $existingTags[] = $tag->id;
            }
            // Adding new tags
            if(is_array($model->tags))
            {
                foreach ($model->tags as $id) {
                    if(!in_array($id, $existingTags)) {
                        $model->addTag($id);
                    }
                }
            }

            $cached_tag_list = [];
            $newTags = $model->getTags();
            foreach ($newTags as $newTag) {
                $cached_tag_list[] = $newTag->name;
            }
            $model->cached_tag_list = implode(', ', $cached_tag_list);

            $model->save();
            return $this->redirect($model->getUrl());
        } 
        $title = 'Изменить запись в блоге';
        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => $title,
            'columnFirst' => [
                'blog_form' => [
                    'view' => '@frontend/views/forms/blog_form',
                    'data' => compact('model', 'tags', 'image', 'title'),
                ],
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(),
            ],
        ]);
    }

    /**
     * Blogs page
     * @param $id int User id
     * @return mixed
     */
    public function actionBlogs($id = false) 
    {
        $query = Post::find()
            ->where([
                'is_public' => 1,
                'content_category_id' => Post::CATEGORY_BLOG,
            ]);
        if($id) {
            $query->andWhere(['user_id' => $id]);
        }
        $query->orderBy(['created_at' => SORT_DESC]);

        $postsDataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Блоги',
            'columnFirst' => [
                'content' => [
                    'view' => '@frontend/views/site/blogs',
                    'data' => compact('postsDataProvider'),
                ],
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(),
            ],
        ]);
    }

    /**
     * Comparing a weight of blocks in columns
     * @param array $a
     * @param array $b
     * @return int Result of comparing
     */
    private static function cmp($a, $b)
    {
        if(!isset($a['weight'])) $a['weight'] = 0;
        if(!isset($b['weight'])) $b['weight'] = 0;
        if ($a['weight'] == $b['weight']) {
            return 0;
        }
        return ($a['weight'] < $b['weight']) ? -1 : 1;
    }
}
