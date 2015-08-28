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
use common\models\Player;
use common\models\Coach;
use common\models\TeamCoach;
use common\models\Album;
use common\models\Banner;
use common\models\VideoPost;
use common\models\Subscribing;
use common\models\Relation;


use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\Response;
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
                'url' => 'http://'.$_SERVER['HTTP_HOST'].'/images/store/post_attachments/', // Directory URL address, where files are stored.
                'path' => '@frontend/web/images/store/post_attachments' // Or absolute path to directory where files are stored.
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'foreColor' => '1667780',
                // 'minLength' => 6,
                // 'maxLength' => 6,
                'width' => 110,
                'height' => 45,
                // 'offset' => -2,
                // 'testLimit' => 3,
                // 'fixedVerifyCode' => 'Dynamomania',
            ],
        ];
    }

    /**
     * Url: /
     * @return mixed Content
     */
    public function actionIndex()
    {
        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col3',
            'title' => 'Dynamomania.com. Сайт болельщиков Динамо Киев',
            'columnFirst' => [
                'top3News' => SiteBlock::getTop3News(),
                'top6News' => SiteBlock::getTop6News(),
                'subscribing' => SiteBlock::getSubscribingForm(),
                'banner1' => SiteBlock::getBanner(Banner::REGION_FIRST_COLUMN),
                'blog_column' => SiteBlock::getBlogPosts(),
            ],
            'columnSecond' => [
                'slider_matches' => SiteBlock::getMatchesSlider(),
                'short_news' => SiteBlock::getshortNews(50, false),
            ],
            'columnThird' => [
                'questionBlock' => SiteBlock::getQuestionBlock(),
                'banner1' => SiteBlock::getBanner(Banner::REGION_THIRD_COLUMN),
                'tournament' => SiteBlock::getTournamentTable(),
                'reviewNews' => SiteBlock::getPhotoVideoNews(),
                'banner2' => SiteBlock::getBanner(Banner::REGION_THIRD_COLUMN),
                'top200tags' => SiteBlock::getTop200Tags(),                
                'banner3' => SiteBlock::getBanner(Banner::REGION_THIRD_COLUMN),
            ],
        ]);
    }

    /**
     * Url: /search?t={$t}
     * Url: /search?q={$q}
     * @param bool|string $t Tag name slug
     * @param bool|string $q Search words
     * @return mixed Content
     * @internal param string $date Searching by date
     */
    public function actionSearch($t = false, $q = false) 
    {
        $t = str_replace('-+-', '-#%#-', $t);
        $t = str_replace('+', ' ', $t);
        $t = str_replace('-#%#-', '+', $t);
        $postTable = Post::tableName();
        $query = Post::find()->where([
            'is_public' => 1, 
        ]);

        if(isset($t) && trim($t) != '') {
            $taggingTable = Tagging::tableName();
            $tagTable = Tag::tableName();
            $query->innerJoin($taggingTable, "{$postTable}.id = {$taggingTable}.taggable_id");
            $query->innerJoin($tagTable, "{$taggingTable}.tag_id = {$tagTable}.id");
            $query->andWhere([
                "{$taggingTable}.taggable_type" => Tagging::TAGGABLE_POST,
                "{$tagTable}.name" => $t,
            ]);
        } elseif(isset($q) && trim($q) != '') {
            $search = addslashes($q);
            $query->andWhere(['or', "MATCH (content) AGAINST ('$search')",['like', 'title', $q]]);
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
            'title' => 'Dynamomania.com | Поиск по сайту',
            'columnFirst' => [
                'news' => [
                    'view' => '@frontend/views/search/search_posts',
                    'data' => compact('newsDataProvider'),
                ],
            ],
            'columnSecond' => [
                'blog_column' => SiteBlock::getBlogPosts(),
                'banner1' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner2' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner3' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner4' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner5' => SiteBlock::getBanner(Banner::REGION_NEWS),
            ],
        ]);
    }

    /**
     * Url: /news/?date={$date}
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
            'title' => 'Dynamomania.com | Новости',
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
                'banner1' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner2' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner3' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner4' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner5' => SiteBlock::getBanner(Banner::REGION_NEWS),
            ],
        ]);
    }
    
    /**
     * Url: /news|blog/{$id}-{$slug}
     * @param $id int Post id
     * @param $slug string Post slug
     * @return string Content
     * @throws NotFoundHttpException
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
            'title' => 'Dynamomania.com | '.$post->title,
            'columnFirst' => [
                'post' => [
                    'view' => '@frontend/views/site/post',
                    'data' => compact('post','image'),
                    'weight' => 0,
                ],
            ],
            'columnSecond' => [
                'short_news' => SiteBlock::getshortNews(50),
            ],

        ];

        $banner = SiteBlock::getBanner(Banner::REGION_UNDER_NEWS);
        $count = 0;
        while($banner){
            $count++;
            $options['columnFirst']['banner-'.$count] = $banner;
            $options['columnFirst']['banner-'.$count]['weight'] = 2;
            $banner = SiteBlock::getBanner(Banner::REGION_UNDER_NEWS);
        }

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
     * If adding is successful, the browser will be redirected to the 'previous' page.
     * 
     * @return mixed
     */
    public function actionCommentAdd() 
    {
        $model = new CommentForm();
        $user = Yii::$app->user;

        $out = ['success' => false];
        if(!$user->can('comment')) {
            $out['message'] = 'Вы забанены';
            return Json::encode($out);
        }
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
     * Url: /matches
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
            ->innerJoinWith('matches')
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
            'title' => 'Dynamomania.com | Матчи',
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
                'tournament' => SiteBlock::getshortNews(50),
            ],
        ]);
    }

    /**
     * Url: /transfers
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
            ->innerJoinWith('transfers')
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
            'title' => 'Dynamomania.com | Трансферы',
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
     * Url: /transfer/{$id}
     * @param int $id Transfer id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionTransfer($id) 
    {
        $transfer = Transfer::findOne($id);
        
        if(!isset($transfer)) {
            throw new NotFoundHttpException('Страница не найдена.');
        }

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | Трансферы | '.$transfer->player->name,
            'columnFirst' => [
                'transfers' => [
                    'view' => '@frontend/views/transfers/transfer_single',
                    'data' => compact('transfer'),
                ],
                'comments' => Comment::getCommentsBlock($transfer->id, Comment::COMMENTABLE_TRANSFER),
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(10),
            ],
        ]);
    }

    /**
     * Url: /tournament
     * @return mixed
     */
    public function actionTournament()
    {
        $tournamentTable = Tournament::tableName();
        $championshipTable = Championship::tableName();

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
            ->innerJoinWith('tournaments')
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
            ->where([
                'season_id' => $activeSeason,
            ])->orderBy([
                'goals' => SORT_DESC,
                'penalty' => SORT_DESC,
            ])->all();

        $options = [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | Турнирная таблица',
            'columnFirst' => [
                'tournament' => [
                    'view' => '@frontend/views/tournament/tournament_full',
                    'data' => compact('tournamentData', 'championshipsData', 'seasonsData'),
                ],
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getshortNews(50),
            ],
        ];

        if(count($forwards) > 0) {
            $options['columnFirst']['forwards'] = [
                'view' => '@frontend/views/tournament/forwards',
                'data' => compact('forwards'),
            ];
        }

        return $this->render('@frontend/views/site/index', $options);
    }

    /**
     * Url: /match/{$id}
     * @param int $id Match id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionMatchTranslation($id) 
    {
        $match = Match::findOne($id);
        
        if(!isset($match)) {
            throw new NotFoundHttpException('Страница не найдена.');
        }

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

        $title = "Онлайн трансляция матча ".$match->teamHome->name." - ".$match->teamGuest->name;
        $columnFirst = [
            'menu' => [
                'view' => '@frontend/views/translation/menu',
                'data' => compact('match'),
                'weight' => 0,
            ],
            'comments' => Comment::getCommentsBlock($match->id, Comment::COMMENTABLE_MATCH),
        ];
        $columnFirst['comments']['weight'] = 10;

        $relation = Relation::find()
            ->where([
                'parent_id' => $match->id,
                'relation_type_id' => Relation::RELATION_ONLINE,
            ])->one();
        
        if(isset($relation)) {
            $post = Post::findOne($relation->relationable_id);
            $columnFirst['translation'] = [
                'view' => '@frontend/views/translation/tpost',
                'data' => compact('match', 'post'),
                'weight' => 5,
            ];
        } else {
            $matchEvents = MatchEvent::find()
                ->where(['match_id' => $match->id])
                ->all();
            $columnFirst['match_preview'] = [
                'view' => '@frontend/views/translation/match_preview',
                'data' => compact('match', 'matchEvents', 'post', 'teamHomePlayers', 'teamGuestPlayers'),
                'weight' => 2,
            ];
            $columnFirst['translation'] = [
                'view' => '@frontend/views/translation/index',
                'data' => compact('match', 'matchEvents'),
                'weight' => 5,
            ];
        }

        // Disable banners if match is online 
        if($match->is_visible && !$match->is_finished && strtotime($match->date) < time())
        {
//             SiteBlock::$banners = [false];
        }

        usort($columnFirst, 'self::cmp');

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | '. $title,
            'columnFirst' => $columnFirst,
            'columnSecond' => [ 
                'tournament' => SiteBlock::getTournamentTable(),
                'short_news' => SiteBlock::getshortNews(50),
            ],
        ]);
    }

    /**
     * Page of all tags
     * Url: /tags
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionTags() 
    {
        $tags = Tag::find()->all();
        
        if(!isset($tags)) {
            throw new NotFoundHttpException('Страница не найдена.');
        }

        $title = "Все теги";

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | '.$title,
            'columnFirst' => [
                'allTags' => [
                    'view' => '@frontend/views/site/tags',
                    'data' => compact('tags'),
                ],                
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(),
            ],
        ]);
    }

    /**
     * Url: /match/protocol/{$id}
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
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

        $teamPlayers = Composition::sortPlayers($teamPlayers);

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
            'title' => 'Dynamomania.com | '.$title,
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
                'short_news' => SiteBlock::getshortNews(50),
            ],
        ]);
    }

    /**
     * Url: /match/{$id}/news
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionMatchNews($id) 
    {
        $match = Match::findOne($id);
        
        if(!isset($match)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $title = "Материалы по матчу ".$match->name;
        
        $postTable = Post::tableName();
        $relationTable = Relation::tableName();

        $query = Post::find()
            ->innerJoin($relationTable, "{$postTable}.id = {$relationTable}.relationable_id")
            ->where([
                'is_public' => 1,
                "{$relationTable}.relation_type_id" => Relation::RELATION_NEWS,
                "{$relationTable}.relationable_type" => Relation::RELATIONABLE_POST,
                "{$relationTable}.parent_id" => $match->id,
            ]);

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
            'title' => 'Dynamomania.com | '.$title,
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
                'short_news' => SiteBlock::getshortNews(50),
            ],
        ]);
    }

    /**
     * Url: /match/{$id}/report
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionMatchReport($id) 
    {
        $match = Match::findOne($id);
        
        if(!isset($match)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $postTable = Post::tableName();
        $relationTable = Relation::tableName();

        $post = Post::find()
            ->innerJoin($relationTable, "{$postTable}.id = {$relationTable}.relationable_id")
            ->where([
                'is_public' => 1,
                "{$relationTable}.relation_type_id" => Relation::RELATION_REPORT,
                "{$relationTable}.relationable_type" => Relation::RELATIONABLE_POST,
                "{$relationTable}.parent_id" => $match->id,
            ])->one();
        
        $options = [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | Отчет по матчу: '.$match->name,
            'columnFirst' => [
                'menu' => [
                    'view' => '@frontend/views/translation/menu',
                    'data' => compact('match'),
                ],
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getshortNews(50),
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
     * Url: /match/{$id}/photos
     * @param $id Match id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionMatchPhotos($id) 
    {
        $match = Match::findOne($id);
        
        if(!isset($match)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $title = "Фото матча ".$match->name;

        $albumTable = Album::tableName();
        $relationTable = Relation::tableName();

        $album = Album::find()
            ->innerJoin($relationTable, "{$albumTable}.id = {$relationTable}.relationable_id")
            ->where([
                'is_public' => 1,
                "{$relationTable}.relationable_type" => Relation::RELATIONABLE_ALBUM,
                "{$relationTable}.parent_id" => $match->id,
            ])->one();
        
        $columnData = [];
        $columnData['menu'] = [
            'view' => '@frontend/views/translation/menu',
            'data' => compact('match'),
        ];

        if (!isset($album)){
            $columnData['content'] = [
                'view' => '@frontend/views/site/empty',
                'data' => ['message' => 'Фотографий к матчу не найдено'],
                'weight' => 10,
            ];
        } else {
            $contentImagesCount = Asset::find()
                ->where([
                    'assetable_id' => $album->id,
                    'assetable_type' => Asset::ASSETABLE_ALBUM,
                    'thumbnail' => Asset::THUMBNAIL_CONTENT,
                ])->count();
            if($contentImagesCount > 0) {
                $imageCount = $contentImagesCount;
                $bigImages = Asset::find()
                    ->where([
                        'assetable_id' => $album->id,
                        'assetable_type' => Asset::ASSETABLE_ALBUM,
                        'thumbnail' => Asset::THUMBNAIL_CONTENT,
                    ])
                    ->limit(12)
                    ->offset(0)
                    ->orderBy(['id' => SORT_ASC])
                    ->all();
                $smallImages = Asset::find()
                    ->where([
                        'assetable_id' => $album->id,
                        'assetable_type' => Asset::ASSETABLE_ALBUM,
                        'thumbnail' => Asset::THUMBNAIL_SMALL,
                    ])
                    ->limit(12)
                    ->offset(0)
                    ->orderBy(['id' => SORT_ASC])
                    ->all();
            } else {
                $imageCount = Asset::find()
                    ->where([
                        'assetable_id' => $album->id,
                        'assetable_type' => Asset::ASSETABLE_ALBUM,
                        'parent_id' => NULL,
                    ])->count();
                $bigImages = Asset::find()
                    ->where([
                        'assetable_id' => $album->id,
                        'assetable_type' => Asset::ASSETABLE_ALBUM,
                    ])
                    ->limit(12)
                    ->offset(0)
                    ->orderBy(['id' => SORT_ASC])
                    ->all();
                $smallImages = $bigImages;
            }

            $columnData['content'] = [
                'view' => '@frontend/views/site/album',
                'data' => compact('album', 'bigImages', 'smallImages', 'imageCount'),
                'weight' => 10,
            ];
            $columnData['comments'] = Comment::getCommentsBlock($album->id, Comment::COMMENTABLE_ALBUM);
            $columnData['comments']['weight'] = 20;
        }
        usort($columnData, 'self::cmp');

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | '.$title,
            'columnFirst' => $columnData,
            'columnSecond' => [ 
                'short_news' => SiteBlock::getshortNews(50),
            ],
        ]);
    }

    /**
     * Url: /match/{$id}/videos
     * @param $id Match id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionMatchVideos($id) 
    {
        $match = Match::findOne($id);
        
        if(!isset($match)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $title = "Видео матча ".$match->name;

        $videoPostTable = VideoPost::tableName();
        $relationTable = Relation::tableName();

        $query = VideoPost::find()
            ->innerJoin($relationTable, "{$videoPostTable}.id = {$relationTable}.relationable_id")
            ->where([
                'is_public' => 1,
                "{$relationTable}.relationable_type" => Relation::RELATIONABLE_VIDEO,
                "{$relationTable}.parent_id" => $match->id,
            ])->orderBy(['created_at' => SORT_DESC]);

        $videosDataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        $emptyText = 'Видеозаписей к матчу не найдено';

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | '.$title,
            'columnFirst' => [
                'menu' => [
                    'view' => '@frontend/views/translation/menu',
                    'data' => compact('match'),
                ],
                'content' => [
                    'view' => '@frontend/views/site/videos',
                    'data' => compact('videosDataProvider', 'emptyText'),
                ],
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getshortNews(50),
            ],
        ]);
    }

    /**
     * Url: /inquirers
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
            'title' => 'Dynamomania.com | Все опросы',
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
     * Url: /inquirers/{$id}
     * @param int $id Inquirer id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionInquirerPage($id)
    {        
        if(!isset($id)) {
            throw new NotFoundHttpException('Страница не найдена.');
        }

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | Опрос',
            'columnFirst' => [
                'inquirer' => SiteBlock::getQuestionBlockTitle(false, $id),
                'comments' => Comment::getCommentsBlock($id, Comment::COMMENTABLE_INQUIRER),
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(10)
            ],
        ]);
    }

    /**
     * Url: /player/{$id}-{$slug}
     * @param int $id Player id
     * @param $slug
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionPlayer($id, $slug)
    {  
        $player = Player::findOne($id);

        if(!isset($player)) {
            throw new NotFoundHttpException('Страница не найдена.');
        }

        $image = $player->getAsset(Asset::THUMBNAIL_CONTENT);

        $options = [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | '.$player->name,
            'columnFirst' => [
                'post' => [
                    'view' => '@frontend/views/site/team_member',
                    'data' => compact('player','image'),
                    'weight' => 0,
                ],
            ],
            'columnSecond' => [
                'blog_column' => SiteBlock::getBlogPosts(),
                'banner1' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner2' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner3' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner4' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner5' => SiteBlock::getBanner(Banner::REGION_NEWS),
            ],

        ];

        return $this->render('@frontend/views/site/index', $options);
    }

    /**
     * Url: /coach/{$id}-{$slug}
     * @param int $id Player id
     * @param $slug
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCoach($id, $slug) 
    {  
        $coach = Coach::findOne($id);

        if(!isset($coach)) {
            throw new NotFoundHttpException('Страница не найдена.');
        }

        $image = $coach->getAsset(Asset::THUMBNAIL_CONTENT);

        $options = [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | '.$coach->name,
            'columnFirst' => [
                'post' => [
                    'view' => '@frontend/views/site/coach',
                    'data' => compact('coach','image'),
                    'weight' => 0,
                ],
            ],
            'columnSecond' => [
                'blog_column' => SiteBlock::getBlogPosts(),
                'banner1' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner2' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner3' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner4' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner5' => SiteBlock::getBanner(Banner::REGION_NEWS),
            ],

        ];

        return $this->render('@frontend/views/site/index', $options);
    }

    /**
     * Url: /complain/{$id}
     * @param int $id Comment id
     * @return mixed
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
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
            $claim->comment_author = $comment->user_id;
            $claim->user_id = Yii::$app->user->id;
        }

        if($claim->load(Yii::$app->request->post())){
            $claim->save();
        }

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | Жалоба на комментарий',
            'columnFirst' => [
                'claim' => [
                    'view' => '@frontend/views/forms/complain_form',
                    'data' => compact('comment', 'claim'),
                ],
            ],
            'columnSecond' => [ 
                'photo_news' => SiteBlock::getPhotoNews(),
                'banner1' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner2' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner3' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner4' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner5' => SiteBlock::getBanner(Banner::REGION_NEWS),
            ],
        ]);
    }

    /**
     * Url: /info|composition|achievements|record-holders/{$id}
     * @param $tab string Team id
     * @param bool|int $id int Team id
     * @return mixed
     * @throws NotFoundHttpException
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
            $availableSeasons = Season::find()
                ->innerJoinWith('contracts')
                ->where(['window' => Season::WINDOW_WINTER])
                ->andWhere(["command_id" => $id])
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
                    'is_active' => 1,
                    'season_id' => $activeSeason,   
                    'command_id' => $team->id,   
                ])
                ->orderBy(['amplua_id' => SORT_ASC])
                ->all();

            $mainCoach = TeamCoach::find()
                ->where([
                    'is_main' => 1,
                    'season_id' => $activeSeason,
                    'team_id' => $id,
                ])->one();

            $teamCoaches = TeamCoach::find()
                ->where([
                    'season_id' => $activeSeason,
                    'team_id' => $id,
                    'is_main' => 0,
                ])->all();
            
            $data = [
                'teamModel' => $team,
                'availableSeasons' => $availableSeasons,
                'activeSeason' => $activeSeason,
                'availableTeams' => $availableTeams,
                'activeTeam' => $team->id,
                'composition' => $composition,
                'mainCoach' => $mainCoach,
                'teamCoaches' => $teamCoaches,
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
            'title' => 'Dynamomania.com | '.$team->name,
            'columnFirst' => [
                'nav-bar' => [
                    'view' => '@frontend/views/team/menu',
                    'data' => compact('team', 'tab'),
                ],
                'content' => [
                    'view' => '@frontend/views/team/tab-'.$tab,
                    'data' => $data,
                ],
            ],
            'columnSecond' => [ 
                'tournament' => SiteBlock::getshortNews(50),
                'banner1' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner2' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner3' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner4' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner5' => SiteBlock::getBanner(Banner::REGION_NEWS),
            ],
        ]);
    }

    /**
     * Url: /post/add
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionPostAdd() 
    {
        if(Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException("Вы не можете выполнить это действие.");
        }

        $model = new Post();
        $model->content_category_id = Post::CATEGORY_BLOG;
        $model->tags = [];
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->allow_comment = 1;
            $model->is_public = 1;
            $model->content_category_id = Post::CATEGORY_BLOG;
            $model->user_id = Yii::$app->user->id;

            // Set slug
            $model->slug = $model->genSlug($model->title);

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
                $uploadedFile = UploadedFile::getInstance($model, 'image');
                if($uploadedFile)
                {
                    // Save origionals 
                    $asset = new Asset();
                    $asset->assetable_type = Asset::ASSETABLE_POST;
                    $asset->assetable_id = $model->id;
                    $asset->uploadedFile = $uploadedFile;
                    $asset->saveAsset();

                    // Save thumbnails 
                    $imageID = $asset->id;
                    $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_POST);

                    foreach ($thumbnails as $thumbnail) {
                        $asset = new Asset();
                        $asset->parent_id = $imageID;
                        $asset->thumbnail = $thumbnail;
                        $asset->assetable_type = Asset::ASSETABLE_POST;
                        $asset->assetable_id = $model->id;
                        $asset->uploadedFile = $uploadedFile;
                        $asset->saveAsset();
                    }
                }
                $model->save(false);
                return $this->redirect($model->getUrl());
            }
            var_dump($model->getErrors());
            die;
        } 
        $title = 'Добавить запись в блог';
        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | '.$title,
            'columnFirst' => [
                'blog_form' => [
                    'view' => '@frontend/views/forms/blog_form',
                    'data' => compact('model', 'tags', 'title'),
                ],
            ],
            'columnSecond' => [ 
                'blog' => SiteBlock::getBlogPosts(),
                'banner1' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner2' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner3' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner4' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner5' => SiteBlock::getBanner(Banner::REGION_NEWS),
            ],
        ]);
    }

    /**
     * Url: /post/edit/{$id}
     * @param $id int Post id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionPostEdit($id) 
    {
        $model = Post::findOne($id);
        if (!isset($model)){
            throw new NotFoundHttpException('Страница не найдена.');
        }

        if($model->content_category_id != Post::CATEGORY_BLOG || 
            empty(Yii::$app->user) || $model->user_id != Yii::$app->user->id) {
            throw new ForbiddenHttpException("Вы не можете выполнить это действие.");
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
            $uploadedFile = UploadedFile::getInstance($model, 'image');
            if($uploadedFile)
            {
                // Remove old assets
                foreach ($assets as $asset) {
                    $asset->delete();
                }

                // Save origionals 
                $asset = new Asset();
                $asset->assetable_type = Asset::ASSETABLE_POST;
                $asset->assetable_id = $model->id;
                $asset->uploadedFile = $uploadedFile;
                $asset->saveAsset();

                // Save thumbnails 
                $imageID = $asset->id;
                $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_POST);

                foreach ($thumbnails as $thumbnail) {
                    $asset = new Asset();
                    $asset->parent_id = $imageID;
                    $asset->thumbnail = $thumbnail;
                    $asset->assetable_type = Asset::ASSETABLE_POST;
                    $asset->assetable_id = $model->id;
                    $asset->uploadedFile = $uploadedFile;
                    $asset->saveAsset();
                }
            }

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
            'title' => 'Dynamomania.com | '.$title,
            'columnFirst' => [
                'blog_form' => [
                    'view' => '@frontend/views/forms/blog_form',
                    'data' => compact('model', 'tags', 'image', 'title'),
                ],
            ],
            'columnSecond' => [ 
                'blog' => SiteBlock::getBlogPosts(),
                'banner1' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner2' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner3' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner4' => SiteBlock::getBanner(Banner::REGION_NEWS),
                'banner5' => SiteBlock::getBanner(Banner::REGION_NEWS),
            ],
        ]);
    }

    /**
     * Url: /blogs
     * @param bool|int $id int User id
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
            'title' => 'Dynamomania.com | Блоги',
            'columnFirst' => [
                'user_comments' => SiteBlock::getUserComments($id),
                'content' => [
                    'view' => '@frontend/views/site/blogs',
                    'data' => compact('postsDataProvider'),
                ],
            ],
            'columnSecond' => [
                'best-blogs' => SiteBlock::getBlogPostsByRating(),
                'short-news' => SiteBlock::getShortNews(),
            ],
        ]);
    }

    /**
     * Url: /information
     * Info page
     * @return mixed
     */
    public function actionInformation()
    {
        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | Информация о сайте',
            'columnFirst' => [
                'content' => [
                    'view' => '@frontend/views/site/information',
                ],
            ],
            'columnSecond' => [
                'banner1' => SiteBlock::getBanner(Banner::REGION_NEWS),
            ],
        ]);
    }

    /**
     * Url: /contacts
     * @return mixed
     */
    public function actionContacts()
    {
        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | Контакты',
            'columnFirst' => [
                'content' => [
                    'view' => '@frontend/views/site/contacts',
                ],
            ],
            'columnSecond' => [
                'banner1' => SiteBlock::getBanner(Banner::REGION_NEWS),
            ],
        ]);
    }

    /**
     * Url: /forum-rules
     * @return mixed
     */
    public function actionForumRules()
    {
        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | Правила форума',
            'columnFirst' => [
                'content' => [
                    'view' => '@frontend/views/site/forum_rules',
                ],
            ],
            'columnSecond' => [
                'short_news' => SiteBlock::getShortNews(50),
            ],
        ]);
    }

    /**
     * Url: /photos
     * Photo page
     * @return mixed
     */
    public function actionPhotos() 
    {
        $query = Album::find()
            ->where([
                'is_public' => 1,
            ]);
        $query->orderBy(['created_at' => SORT_DESC]);

        $albumsDataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | Фото',
            'columnFirst' => [
                'content' => [
                    'view' => '@frontend/views/site/photos',
                    'data' => compact('albumsDataProvider'),
                ],
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(),
            ],
        ]);
    }

    /**
     * Url: /videos
     * Video page
     * @return mixed
     */
    public function actionVideos() 
    {
        $query = VideoPost::find()
            ->where([
                'is_public' => 1,
            ]);
        $query->orderBy(['created_at' => SORT_DESC]);

        $videosDataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | Видео',
            'columnFirst' => [
                'content' => [
                    'view' => '@frontend/views/site/videos',
                    'data' => compact('videosDataProvider'),
                ],
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getShortNews(),
            ],
        ]);
    }

    /**
     * Url: /video/{$id}-{$slug}
     * @param int $id Album id
     * @param string $slug Album slug
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionVideoPost($id, $slug) 
    {
        $videoPost = VideoPost::find()
            ->where([
                'id' => $id,
                'is_public' => 1,
            ])->one();

        if (!isset($videoPost)){
            throw new NotFoundHttpException('Страница не найдена.');
        }

        $image = $videoPost->getAsset();
        $video = $videoPost->getVideoAsset();

        $options = [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | Видео: '.$videoPost->title,
            'columnFirst' => [
                'content' => [
                    'view' => '@frontend/views/site/video_post',
                    'data' => compact('videoPost', 'image', 'video'),
                ],
            ],
            'columnSecond' => [
                'short_news' => SiteBlock::getShortNews(),
            ],
        ];

        $banner = SiteBlock::getBanner(Banner::REGION_UNDER_NEWS);
        $count = 0;
        while($banner){
            $count++;
            $options['columnFirst']['banner-'.$count] = $banner;
            $options['columnFirst']['banner-'.$count]['weight'] = 2;
            $banner = SiteBlock::getBanner(Banner::REGION_UNDER_NEWS);
        }

        $options['columnFirst']['comments'] = Comment::getCommentsBlock($videoPost->id, Comment::COMMENTABLE_VIDEO);
        $options['columnFirst']['comments']['weight'] = 5;

        usort($options['columnFirst'],'self::cmp');

        return $this->render('@frontend/views/site/index', $options);
    }


    /**
     * Url: /album/{$id}-{$slug}
     * @param int $id Album id
     * @param string $slug Album slug
     * @return mixed Content
     * @throws NotFoundHttpException
     */
    public function actionAlbum($id, $slug) 
    {
        $album = Album::find()
            ->where([
                'id' => $id,
                'is_public' => 1,
            ])->one();

        if (!isset($album)){
            throw new NotFoundHttpException('Страница не найдена.');
        }

        $contentImagesCount = Asset::find()
            ->where([
                'assetable_id' => $id,
                'assetable_type' => Asset::ASSETABLE_ALBUM,
                'thumbnail' => Asset::THUMBNAIL_CONTENT,
            ])->count();
        if($contentImagesCount > 0) {
            $imageCount = $contentImagesCount;
            $bigImages = Asset::find()
                ->where([
                    'assetable_id' => $id,
                    'assetable_type' => Asset::ASSETABLE_ALBUM,
                    'thumbnail' => Asset::THUMBNAIL_CONTENT,
                ])
                ->limit(12)
                ->offset(0)
                ->orderBy(['id' => SORT_ASC])
                ->all();
            $smallImages = Asset::find()
                ->where([
                    'assetable_id' => $id,
                    'assetable_type' => Asset::ASSETABLE_ALBUM,
                    'thumbnail' => Asset::THUMBNAIL_SMALL,
                ])
                ->limit(12)
                ->offset(0)
                ->orderBy(['id' => SORT_ASC])
                ->all();
        } else {
            $imageCount = Asset::find()
                ->where([
                    'assetable_id' => $id,
                    'assetable_type' => Asset::ASSETABLE_ALBUM,
                    'parent_id' => NULL,
                ])->count();
            $bigImages = Asset::find()
                ->where([
                    'assetable_id' => $id,
                    'assetable_type' => Asset::ASSETABLE_ALBUM,
                ])
                ->limit(12)
                ->offset(0)
                ->orderBy(['id' => SORT_ASC])
                ->all();
            $smallImages = $bigImages;
        }

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | Фотоальбом: '.$album->title,
            'columnFirst' => [
                'content' => [
                    'view' => '@frontend/views/site/album',
                    'data' => compact('album', 'bigImages', 'smallImages', 'imageCount'),
                ],
                'comments' => Comment::getCommentsBlock($album->id, Comment::COMMENTABLE_ALBUM),
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getshortNews(50),
            ],
        ]);
    }

    /**
     * @param int $id Album id
     * @param int $count Exist amount of images
     * @return mixed Images to bxSlider
     */
    public function actionAlbumLoadImages($id, $count) 
    {
        $album = Album::findOne($id);

        $contentImagesCount = Asset::find()
            ->where([
                'assetable_id' => $id,
                'assetable_type' => Asset::ASSETABLE_ALBUM,
                'thumbnail' => Asset::THUMBNAIL_CONTENT,
            ])->count();
        $query = Asset::find()
            ->where([
                'assetable_id' => $id,
                'assetable_type' => Asset::ASSETABLE_ALBUM,
            ]);
        if($contentImagesCount > 0) {
            $query->andWhere([
                'thumbnail' => Asset::THUMBNAIL_CONTENT,
            ]);
        } 
        $images = $query->limit(12)
            ->offset($count)
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $thumbnailImages = [];
        $contentImagesHtml = '';
        $thumbnailImagesHtml = '';
        foreach ($images as $image) {
            if(isset($image->parent_id)) {
                $thumbnailImage = Asset::find()
                    ->where([
                        'parent_id' => $image->parent_id,
                        'thumbnail' => Asset::THUMBNAIL_SMALL,
                    ])->one();
                $thumbnailImages[] = isset($thumbnailImage->id) ? $thumbnailImage : $image;
            } else {
                $thumbnailImages[] = $image;
            }
            $contentImagesHtml .= '<div><a href="'. $album->getPhotoUrl($image->id) .'">'.
                '<img src="'.$image->getFileUrl().'" alt="slide">'.
                '</a></div>';
        }
        foreach ($thumbnailImages as $image) {
            $thumbnailImagesHtml .= '<a class="pager-item" data-slide-index="'.$count.'" href="javascript:void(0)"><img src="'.$image->getFileUrl().'" alt="slide"></a>';
            $count++;
        }

        return Json::encode(compact('contentImagesHtml','thumbnailImagesHtml'));
    }

    /**
     * Url: /album/{$album_id}-{$slug}/{$photo_id}
     * @param int $album_id Album id
     * @param string $slug Album slug
     * @param $photo_id
     * @return mixed Content
     * @throws NotFoundHttpException
     */
    public function actionPhoto($album_id, $slug, $photo_id) 
    {
        $album = Album::find()
            ->where([
                'id' => $album_id,
                'is_public' => 1,
            ])->one();

        if (!isset($album)){
            throw new NotFoundHttpException('Страница не найдена.');
        }

        $photo = Asset::find()
            ->where([
                'id' => $photo_id,
                'thumbnail' => Asset::THUMBNAIL_CONTENT,
            ])->one();
        if (!isset($photo)){
            $photo = Asset::find()
            ->where([
                'id' => $photo_id,
            ])->one();
        }

        if (!isset($photo)){
            throw new NotFoundHttpException('Страница не найдена.');
        }

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Dynamomania.com | Фотоальбом: '.$album->title,
            'columnFirst' => [
                'content' => [
                    'view' => '@frontend/views/site/photo_single',
                    'data' => compact('album', 'photo'),
                ],
                'comments' => Comment::getCommentsBlock($photo->id, Comment::COMMENTABLE_PHOTO),
            ],
            'columnSecond' => [ 
                'short_news' => SiteBlock::getshortNews(50),
            ],
        ]);
    }

    /**
     * Url: /unsubscribe/{$key}
     * @param $key string
     * @return mixed 
     */
    public function actionUnsubscribe($key)
    {
        $subscribings = Subscribing::find()->all();
        foreach ($subscribings as $subscribing) {
            if(md5($subscribing->id.$subscribing->email) === $key) {
                Yii::$app->session->setFlash("success-unsubscribe", "Ваш email ".$subscribing->email." успешно отписан от рассылки новостей.");
                $subscribing->delete();
            }
        }
        return $this->redirect(Url::to('/'));
        
    }

    /**
     * Url: /rss.xml
     * @return mixed 
     */
    public function actionNewsRss()
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml; charset=utf-8');

        $posts = Post::find()
            ->where(['is_public' => 1])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(50)
            ->all();
        $items = [];
        foreach ($posts as $post) {
            $content = $post->getShortContent(500, 700);
            $authorName = isset($post->user) ? $post->user->getDisplayName() : false;
            $image = $post->getAsset(Asset::THUMBNAIL_CONTENT);
            $enclosureUrl = file_exists($image->getFilePath()) ? $image->getFileUrl() : false;
            $enclosureType = $enclosureUrl ? image_type_to_mime_type(exif_imagetype($image->getFilePath())) : false;
            $item = [
                'title' => htmlspecialchars($post->title),
                'link' => $post->url,
                'description' => htmlspecialchars($content),
                'pubDate' => date('r', strtotime($post->created_at)),
                'authorName' => $authorName,
                'enclosureUrl' => $enclosureUrl,
                'enclosureType' => $enclosureType,
            ];
            $items[] = (object) $item;
        }
        $title = 'Динамомания: Новости';
        $description = 'Лента последних новостей';
        return $this->renderPartial('@frontend/views/site/rss', compact(
                'title',
                'description',
                'items'
            )
        );
    }

    /**
     * Url: /match/rss.xml
     * Rss output of last match events
     * @return mixed 
     */
    public function actionEventsRss()
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml; charset=utf-8');

        $matches = Match::find()
            ->innerJoinWith('matchEvents')
            ->orderBy(['date' => SORT_DESC])
            ->groupBy(Match::tableName().'.id')
            ->limit(10)
            ->all();

        $translations = [];
        $yandexSportAPI = file_get_contents("http://api.sport.yandex.ru/public/events.xml");
        foreach ($matches as $match) {
            $events = MatchEvent::find()
                ->where(['not', ['is_hidden' => 1]])
                ->andWhere(['match_id' => $match->id])
                ->orderBy(['created_at' => SORT_DESC])
                ->all();
            // yandexTeamId Dynamo 78662
            // competition_id Y 1999 D 2 Ukrainian Premier League
            // competition_id = 2004 D 12 UPL Supercup 2015
            // competition_id = 1998 D 5 UEFA Champions League
            // competition_id = 000000021099 D 20 European Championship Qualification

            if($match->championship_id == 2) {
                $competition = 1999;
            } elseif($match->championship_id == 12) {
                $competition = 2004;
            } elseif($match->championship_id == 5) {
                $competition = 1998;
            } elseif($match->championship_id == 20) {
                $competition = '000000021099';
            } else {
                $competition = 0;
            }
            if(!$competition) continue;

            $matches = [];
            $matchDate = date('Y-m-d', strtotime($match->date));
            $pattern = "@<event.*competition=\"$competition\".*id=\"(.*)\".*start_date=\"$matchDate.*/>@U";
            preg_match($pattern, $yandexSportAPI, $matches);
            $eventID = count($matches) && isset($matches[1]) ? $matches[1] : 0;

            $translation = [
                'link' => Url::to('/match/'.$match->id),
                'id' => $match->id,
                'competition_id' => $competition,
                'event_id' => $eventID,
                'comments' => [],
            ];
            $minute = 0;
            $comments = [];
            foreach ($events as $event) {
                $minute = $event->minute ? $event->minute : $minute;
                $comment = [
                    'id' => $event->id,
                    'time' => $minute,
                    'text' => htmlspecialchars(strip_tags($event->notes,"<a><p><br>")),
                ];
                $comments[] = (object) $comment;
            }
            $translation['comments'] = $comments;
            $translations[] = (object) $translation;
        }

        return $this->renderPartial('@frontend/views/site/translation_rss', compact(
                'translations'
            )
        );
    }

    /**
     * Url: /yandex|vk|tw|fb/rss.xml
     * @param $kind
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionSocialRss($kind)
    {
        $kind = 'is_'.$kind.'_rss';
        $post = new Post();
        if(!$post->hasAttribute($kind)) {
            throw new NotFoundHttpException('Страница не найдена.');
        }

        Yii::$app->response->format = Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml; charset=utf-8');

        $posts = Post::find()
            ->where([
                'is_public' => 1,
                $kind => 1,
            ])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(50)
            ->all();
        $items = [];
        foreach ($posts as $post) {
            $content = $post->getShortContent(500, 700);
            $fulltext = strip_tags($post->content,'<p><a><br>');
            $authorName = isset($post->user) ? $post->user->getDisplayName() : false;
            $image = $post->getAsset(Asset::THUMBNAIL_CONTENT);
            $enclosureUrl = file_exists($image->getFilePath()) ? $image->getFileUrl() : false;
            $enclosureType = $enclosureUrl ? image_type_to_mime_type(exif_imagetype($image->getFilePath())) : false;
            $item = [
                'title' => htmlspecialchars($post->title),
                'link' => $post->url,
                'description' => htmlspecialchars($content),
                'fulltext' => htmlspecialchars($fulltext),
                'pubDate' => date('r', strtotime($post->created_at)),
                'authorName' => $authorName,
                'enclosureUrl' => $enclosureUrl,
                'enclosureType' => $enclosureType,
            ];
            $items[] = (object) $item;
        }
        $title = 'Динамомания: Новости';
        $description = 'Лента последних новостей';
        if($kind == 'is_yandex_rss') $view = '@frontend/views/site/yandex_rss';
        else $view = '@frontend/views/site/rss';
        return $this->renderPartial($view, compact(
                'title',
                'description',
                'items'
            )
        );
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
