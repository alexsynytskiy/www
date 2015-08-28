<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\data\Pagination;

class SiteBlock
{
    static $postExcludeIds = [];
    static $banners = [];
    static $postedBannerIds = [];

    /**
     * Get all bannerModels
     * @return array of \common\models\Banner
     */
    public static function getBannerModels()
    {
        if(count(self::$banners) > 0) return self::$banners;

        $banners = Banner::find()
            ->orderBy(['weight' => SORT_DESC])
            ->all();
        self::$banners = $banners;

        return self::$banners;
    }

    /**
     * @param $region
     * @param bool $big
     * @return array Data
     */
    public static function getBanner($region, $big = false)
    {
        $regions = Banner::dropdownRegions();
        $regions = array_keys($regions);
        if(!in_array($region, $regions)) return false;

        $allBanners = self::getBannerModels();
        foreach ($allBanners as $banner) {
            if(!$banner) continue;
            if(in_array($banner->id, self::$postedBannerIds)) continue;
            if($big && !$banner->size) continue;
            if($banner->region != $region) continue;
            self::$postedBannerIds[] = $banner->id;
            $block = [
                'view' => '@frontend/views/blocks/banner_block',
                'data' => compact('banner'),
            ];
            return $block;
        }
        return false;
    }

    /**
     * Get block with last 3 blog posts selected by site, and 3 last blog posts, which are not in selected
     * @return array Data
     */
    public static function getBlogPosts($cache = true)
    {
        if($cache)
        {
            $cacheBlock = CacheBlock::find()
                ->where(['machine_name' => 'lastBlogPosts'])
                ->one();
            if(isset($cacheBlock)) {
                return [
                    'view' => '@frontend/views/blocks/cache_block',
                    'data' => ['content' => $cacheBlock->content],
                ];
            }
        } 

        $postTable = Post::tableName();
        $selectedBlogsTable = SelectedBlog::tableName();
        $selectedBlogs = Post::find()
            ->innerJoin($selectedBlogsTable, "{$postTable}.id = {$selectedBlogsTable}.post_id")
            ->where(['is_public' => 1])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(3)
            ->all();

        $selectedID = [];
        foreach ($selectedBlogs as $blog) {
            $selectedID[] = $blog->id;
        }

        $posts = Post::find()
            ->where([
                'is_public' => 1,
                'content_category_id' => Post::CATEGORY_BLOG
                ])
            ->andWhere(['not in', 'id', $selectedID])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(3)
            ->all();        

        $block = [
            'view' => '@frontend/views/blocks/blog_block',
            'data' => compact('posts', 'selectedBlogs'),
        ];
        if(!$cache) {
            $view = new \yii\base\View();
            return $view->renderFile($block['view'].'.php', $block['data']);
        }
        return $block;
    }

    /**
     * Get block with 3 blog posts, which are best by rating during last 3 months
     * @return array Data
     */
    public static function getBlogPostsByRating($cache = true)
    {
        if($cache)
        {
            $cacheBlock = CacheBlock::find()
                ->where(['machine_name' => 'blogPostsByRating'])
                ->one();
            if(isset($cacheBlock)) {
                return [
                    'view' => '@frontend/views/blocks/cache_block',
                    'data' => ['content' => $cacheBlock->content],
                ];
            }
        } 

        $connection = Yii::$app->db;
        $blogsIDQuery = 'SELECT id
                    FROM posts
                    WHERE created_at > DATE_SUB(NOW(), INTERVAL 90 day) AND content_category_id = '.Post::CATEGORY_BLOG;

        //INNER JOIN votes ON post.id = votes.voteable_id AND votes.voteable_type = '.Vote::VOTEABLE_POST.'

        $cmd = $connection->createCommand($blogsIDQuery);
        $bestBlogs = $cmd->queryAll();

        $ratingArray = [];
        foreach ($bestBlogs as &$blog) {
            $blog['rating'] = Vote::getRating($blog['id'], Vote::VOTEABLE_POST);
        }

        for ($i = 0; $i < count($bestBlogs) - 1; $i++) {
            for ($j = $i + 1; $j < count($bestBlogs); $j++) {
                if($bestBlogs[$j]['rating'] > $bestBlogs[$i]['rating']) {
                    $temp = $bestBlogs[$j];
                    $bestBlogs[$j] = $bestBlogs[$i];
                    $bestBlogs[$i] = $temp;
                }
            }
        }

        $count = 3;
        if (count($bestBlogs) < 3) {
            $count = count($bestBlogs);
        }

        $best3Blogs = [];
        for ($i = 0; $i < $count; $i++) {
            $best3Blogs[] = $bestBlogs[$i]['id'];
        }

        $blogs = Post::findAll($best3Blogs);

        $block = [
            'view' => '@frontend/views/blocks/blog_block_rating',
            'data' => compact('blogs'),
        ];
        if(!$cache) {
            $view = new \yii\base\View();
            return $view->renderFile($block['view'].'.php', $block['data']);
        }
        return $block;
    }

    /**
     * Get top 200 tags to cloud
     * @return array Data
     */
    public static function getTop200Tags()
    {
        $topTags = TagsCloud::find()
            ->orderBy(['weight' => SORT_DESC])
            ->all();

        $block = [
            'view' => '@frontend/views/blocks/top_tags_block',
            'data' => ['topTags' => $topTags],
        ];
        return $block;
    }

    /**
     * Get block with last $amount news
     * @return mixed Data
     */
    public static function getShortNews($amount = 50, $enableBanners = true, $cache = true, $newsPosts = null)
    {   
        if($cache)
        {
            $banners = $enableBanners ? 'banners' : '';
            $cacheBlock = CacheBlock::find()
                ->where(['machine_name' => 'shortNews'.$amount.$banners])
                ->one();
            if(isset($cacheBlock)) {
                return [
                    'view' => '@frontend/views/blocks/cache_block',
                    'data' => ['content' => $cacheBlock->content],
                ];
            }
        } 

        if(!isset($newsPosts)) 
        {
            $newsPosts = Post::find()
                ->where(['is_public' => 1, 'content_category_id' => Post::CATEGORY_NEWS])
                ->orderBy(['created_at' => SORT_DESC])
                ->limit($amount)
                ->all();
        }
        $block = [
            'view' => '@frontend/views/blocks/news_block',
            'data' => ['posts' => $newsPosts, 'enableBanners' => $enableBanners],
        ];
        if(!$cache) {
            $view = new \yii\base\View();
            return $view->renderFile($block['view'].'.php', $block['data']);
        }
        return $block;
    }

    /**
     * Get block with top 3 news
     * @return array Data
     */
    public static function getTop3News($cache = true)
    {
        if($cache)
        {
            $cacheBlock = CacheBlock::find()
                ->where(['machine_name' => 'top3News'])
                ->one();
            if(isset($cacheBlock)) {
                return [
                    'view' => '@frontend/views/blocks/cache_block',
                    'data' => ['content' => $cacheBlock->content],
                ];
            }
        } 

        $postTable = Post::tableName();
        $assetTable = Asset::tableName();
        
        // TOP 3
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

        self::$postExcludeIds = [];
        foreach ($top3News as $post) {
            self::$postExcludeIds[] = $post->id;
        }

        $block = [
            'view' => '@frontend/views/blocks/main_slider_block',
            'data' => ['top3News' => $top3News],
        ];
        if(!$cache) {
            $view = new \yii\base\View();
            return $view->renderFile($block['view'].'.php', $block['data']);
        }
        return $block;
    }

    /**
     * Get block with top 6 news
     * @return array Data
     */
    public static function getTop6News($cache = true)
    {
        if($cache)
        {
            $cacheBlock = CacheBlock::find()
                ->where(['machine_name' => 'top6News'])
                ->one();
            if(isset($cacheBlock)) {
                return [
                    'view' => '@frontend/views/blocks/cache_block',
                    'data' => ['content' => $cacheBlock->content],
                ];
            }
        }

        $postTable = Post::tableName();
        $assetTable = Asset::tableName();
        
        // TOP 6
        $query = Post::find()
            ->innerJoin($assetTable, "{$assetTable}.assetable_id = {$postTable}.id")
            ->where([
                'is_public' => 1, 
                'is_top' => 1, 
                'content_category_id' => Post::CATEGORY_NEWS,
                "{$assetTable}.assetable_type" => Asset::ASSETABLE_POST,
                "{$assetTable}.thumbnail" => Asset::THUMBNAIL_NEWS,
            ]);
        $top6News = $query->andWhere(['not in', "{$postTable}.id", self::$postExcludeIds])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(6)
            ->all();

        foreach ($top6News as $post) {
            self::$postExcludeIds[] = $post->id;
        }

        $block = [
            'view' => '@frontend/views/blocks/main_news_block',
            'data' => ['top6News' => $top6News],
        ];
        if(!$cache) {
            $view = new \yii\base\View();
            return $view->renderFile($block['view'].'.php', $block['data']);
        }
        return $block;
    }

    /**
     * Get block with photo and video reviews
     * @return array Data
     */
    public static function getPhotoVideoNews()
    {
        $postTable = Post::tableName();
        $assetTable = Asset::tableName();

        // Photo review
        $photoReviewNews = Album::find()
            ->where([
                'is_public' => 1, 
            ])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(3)
            ->all();

        // Video review
        $videoReviewNews = VideoPost::find()
            ->where([
                'is_public' => 1, 
            ])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(3)
            ->all();

        if(count($photoReviewNews) == 0 && count($videoReviewNews) == 0) {
            return false;
        }

        $block = [
            'view' => '@frontend/views/blocks/review_news_block',
            'data' => compact('photoReviewNews','videoReviewNews'),
        ];
        return $block;
    }

    /**
     * Get block with photo reviews
     * @return array Data
     */
    public static function getPhotoNews()
    {
        // Photo review
        $photoReviewNews = Album::find()
            ->where([
                'is_public' => 1, 
            ])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(3)
            ->all();

        if(count($photoReviewNews) == 0) return false;

        $block = [
            'view' => '@frontend/views/blocks/review_news_block',
            'data' => compact('photoReviewNews'),
        ];
        return $block;
    }

    /**
     * Get block with video reviews
     * @return array Data
     */
    public static function getVideoNews()
    {
        $postTable = Post::tableName();
        $assetTable = Asset::tableName();

        // Video review
        $videoReviewNews = VideoPost::find()
            ->where([
                'is_public' => 1, 
            ])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(3)
            ->all();

        if(count($videoReviewNews) == 0) {
            return false;
        }

        $block = [
            'view' => '@frontend/views/blocks/review_news_block',
            'data' => compact('videoReviewNews'),
        ];
        return $block;
    }

    /**
     * Get block with slider about previous and future matches
     * @return array Data
     */
    public static function getMatchesSlider()
    {
        $selectTeamsOI = [
            Team::TEAM_DK_FIRST_FULL_NAME,
            Team::TEAM_UKRAINE,
        ];

        $sliderPreviousMatches = Match::find()
            ->where(['is_visible' => 1])
            ->andWhere(['<', 'date', date('Y-m.d H:i:s')])
            ->andWhere(['or', 
                ["command_home_id" => $selectTeamsOI[0]],
                ["command_guest_id" => $selectTeamsOI[0]],
                ["command_home_id" => $selectTeamsOI[1]], 
                ["command_guest_id" => $selectTeamsOI[1]],
            ])
            ->orderBy(['date' => SORT_DESC])
            ->limit(5)
            ->all();

        $sliderPreviousMatches = array_reverse($sliderPreviousMatches);

        $sliderFutureMatches = Match::find()
            ->where(['>', 'date', date('Y-m.d H:i:s')])
            ->andWhere(['or', 
                ["command_home_id" => $selectTeamsOI[0]],
                ["command_guest_id" => $selectTeamsOI[0]],
                ["command_home_id" => $selectTeamsOI[1]], 
                ["command_guest_id" => $selectTeamsOI[1]],
            ])
            ->orderBy(['date' => SORT_ASC])
            ->limit(5)
            ->all();        

        $sliderMatches = array_merge($sliderPreviousMatches, $sliderFutureMatches);

        $block = [
            'view' => '@frontend/views/blocks/matches_slider_block',
            'data' => ['matches' => $sliderMatches],
        ];
        return $block;
    }

    /**
     * Get block with last 50 news
     * @return array Data
     */
    public static function getTournamentTable()
    {
        $lastSeason = Tournament::find()->max("season_id");

        $teams = Tournament::find()
            ->where(['season_id' => $lastSeason])
            ->orderBy(['points' => SORT_DESC])
            ->all();

        $block = [
            'view' => '@frontend/views/blocks/tournament_block',
            'data' => ['teams' => $teams],
        ];
        return $block;
    }

    public static function getQuestionBlock($question = false)
    {
        if(!$question) {
            $question = Question::find()
                ->where(['is_active' => 1])
                ->orderBy(['created_at' => SORT_DESC])
                ->one();
        }
        if(!isset($question)) {
            $question = Question::find()
                ->where(['parent_id' => null])
                ->orderBy(['created_at' => SORT_DESC])
                ->one();
        }

        $uid = isset(Yii::$app->user->id) ? Yii::$app->user->id : 0;
        $userVote = QuestionVote::find()
            ->where([
                'question_id' => $question->id,
                'user_id' => $uid,
            ])->one();

        if(isset($userVote->id) || !$question->is_active){
            $block = true;
        } else {
            $block = false;
        }

        $answers = Question::find()
            ->where(['parent_id' => $question->id])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        if($block){
            $view = $question->is_float ? 'blocks/question_float_block' : 'blocks/question_block';
        } else {
            $view = $question->is_float ? 'forms/question_float_form' : 'forms/question_form';
        }

        $block = [
            'view' => '@frontend/views/'.$view,
            'data' => compact('question', 'answers'),
        ];
        return $block;
    }

    /**
     * Get question block
     * @param \common\models\Question|bool|false $question
     * @param int $id
     * @return array Data
     */
    public static function getQuestionBlockTitle($question = false, $id = NULL)
    {
        if(isset($id)) {
            $question = Question::find()
                ->where(['id' => $id])
                ->orderBy(['created_at' => SORT_DESC])
                ->one();
        }
        else if(!$question) {
            $question = Question::find()
                ->where(['is_active' => 1])
                ->orderBy(['created_at' => SORT_DESC])
                ->one();
        }

        if(!isset($question)) {
            $question = Question::find()
                ->where(['parent_id' => null])
                ->orderBy(['created_at' => SORT_DESC])
                ->one();
        }

        $uid = isset(Yii::$app->user->id) ? Yii::$app->user->id : 0;
        $userVote = QuestionVote::find()
            ->where([
                'question_id' => $question->id,
                'user_id' => $uid,
            ])
            ->one();

        if(isset($userVote->id) || !$question->is_active){
            $block = true;
        } else {
            $block = false;
        }

        $answers = Question::find()
            ->where(['parent_id' => $question->id])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        if(!isset($id)) {
            if($block){
                $view = $question->is_float ? 'blocks/question_float_block_title' : 'blocks/question_block_title';
            } else {
                $view = $question->is_float ? 'forms/question_float_form' : 'forms/question_form';
            }
        }
        else {
            if($block){
                $view = $question->is_float ? 'blocks/question_float_block' : 'blocks/question_block';
            } else {
                $view = $question->is_float ? 'forms/question_float_form' : 'forms/question_form';
            }
        }

        $block = [
            'view' => '@frontend/views/'.$view,
            'data' => compact('question', 'answers'),
        ];
        return $block;
    }

    /**
     * Get a subscribing form
     * @return array Data
     */
    public static function getSubscribingForm()
    {
        $model = new Subscribing();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if(!$model->save()) {
                $errors = $model->getErrors();
                $errorMessage = array_shift($errors);
                Yii::$app->getSession()->setFlash('error-subscribe', 'Произошла ошибка: '.$errorMessage);
            }
            Yii::$app->getSession()->setFlash('success-subscribe', 'Вы успешно подписались на новостную рассылку от dynamomania.com');
            return Yii::$app->getResponse()->redirect(Url::to('/'));
        } 
        $block = [
            'view' => '@frontend/views/forms/subscribing_form',
            'data' => compact('model'),
        ];
        return $block;
    }

    public static function getUserComments($id)
    {
        $connection = Yii::$app->db;
        $countSql = 'SELECT COUNT(*) as count
                FROM comments c1
                LEFT JOIN posts p ON p.id = c1.commentable_id
                WHERE c1.user_id = :user_id AND c1.id IN (
                    SELECT c2.parent_id
                    FROM comments c2
                    WHERE c2.parent_id = c1.id
                )';
        $cmd = $connection->createCommand($countSql);
        $cmd->bindValue(':user_id', $id);
        $commentsCountData = $cmd->queryAll();
        $commentsCount = $commentsCountData[0]['count'];

        $commentsPagination = new Pagination([
            'totalCount' => $commentsCount,
            'pageSize' => 10,
            'pageParam' => 'cpage',
            'pageSizeParam' => 'cpsize',
        ]);

        // AND c1.parent_id IS NULL
        $sql = 'SELECT c1.id
                FROM comments c1
                LEFT JOIN posts p ON p.id = c1.commentable_id
                WHERE c1.user_id = :user_id AND c1.id IN (
                    SELECT c2.parent_id
                    FROM comments c2
                    WHERE c2.parent_id = c1.id
                )
                ORDER BY c1.created_at DESC
                LIMIT :offset, :rows';
        $cmd = $connection->createCommand($sql);
        $cmd->bindValue(':user_id', $id);
        $cmd->bindValue(':offset', $commentsPagination->offset);
        $cmd->bindValue(':rows', $commentsPagination->limit);
        $commentsData = $cmd->queryAll();

        $ids = [];
        foreach ($commentsData as $data) {
            $ids[] = $data['id'];
        }

        $initialComments = Comment::find()
            ->where([
                'id' => $ids,
            ])->orderBy(['created_at' => SORT_DESC])
            ->all();

        $comments = $initialComments;
        $ids = [];
        foreach ($comments as $comment) {
            $ids[] = $comment->id;
        }
        $childComments = Comment::find()
            ->where(['parent_id' => $ids])->orderBy(['created_at' => SORT_ASC])->all();
        if (count($childComments) > 0) {
            $initialComments = array_merge($initialComments, $childComments);
        }

        $parentIDs = [];
        foreach ($initialComments as $comment) {
            if ($comment->parent_id != null) $parentIDs[] = $comment->parent_id;
        }

        $sortedComments = [];
        foreach ($initialComments as $comment) {
            if ($comment->parent_id == null
                || $comment->user_id == $id
                && in_array($comment->id, $parentIDs)
            ) {
                $index = 0;
            } else {
                $index = $comment->parent_id;
            }
            $sortedComments[$index][] = $comment;
        }
        $block = [
            'view' => '@frontend/views/site/blogs_user_comments',
            'data' => [
                'comments' => $sortedComments,
                'pagination' => $commentsPagination,
            ],
        ];
        return $block;
    }

}