<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use amnah\yii2\user\models\User;

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
     * Get banner
     * @return array Data
     */
    public static function getBanner($region, $big = false)
    {
        $regions = Banner::dropdownRegions();
        $regions = array_keys($regions);
        if(!in_array($region, $regions)) return false;

        $allBanners = self::getBannerModels();
        foreach ($allBanners as $banner) {
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
     * Get block with last 6 blog posts
     * @return array Data
     */
    public static function getBlogPosts()
    {
        $blogPosts = Post::find()
            ->where(['is_public' => 1, 'content_category_id' => Post::CATEGORY_BLOG])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(6)
            ->all();

        $block = [
            'view' => '@frontend/views/blocks/blog_block',
            'data' => ['posts' => $blogPosts],
        ];
        return $block;
    }

    /**
     * Get block with last 50 news
     * @return array Data
     */
    public static function getShortNews()
    {
        $newsPosts = Post::find()
            ->where(['is_public' => 1, 'content_category_id' => Post::CATEGORY_NEWS])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(50)
            ->all();

        $block = [
            'view' => '@frontend/views/blocks/news_block',
            'data' => ['posts' => $newsPosts],
        ];
        return $block;
    }

    /**
     * Get block with top 3 news
     * @return array Data
     */
    public static function getTop3News()
    {
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
        return $block;
    }

    /**
     * Get block with top 6 news
     * @return array Data
     */
    public static function getTop6News()
    {
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
        $query = Post::find()
            ->innerJoin($assetTable, "{$assetTable}.assetable_id = {$postTable}.id")
            ->where([
                'is_public' => 1, 
                'with_photo' => 1,
                'content_category_id' => Post::CATEGORY_NEWS,
                "{$assetTable}.assetable_type" => Asset::ASSETABLE_POST,
                "{$assetTable}.thumbnail" => Asset::THUMBNAIL_BIG,
            ]);
        $photoReviewNews = $query->andWhere(['not in', "{$postTable}.id", self::$postExcludeIds])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(3)
            ->all();

        foreach ($photoReviewNews as $post) {
            self::$postExcludeIds[] = $post->id;
        }

        // Video review
        $query = Post::find()
            ->innerJoin($assetTable, "{$assetTable}.assetable_id = {$postTable}.id")
            ->where([
                'is_public' => 1, 
                'with_video' => 1,
                'content_category_id' => Post::CATEGORY_NEWS,
                "{$assetTable}.assetable_type" => Asset::ASSETABLE_POST,
                "{$assetTable}.thumbnail" => Asset::THUMBNAIL_BIG,
            ]);
        $videoReviewNews = $query->andWhere(['not in', "{$postTable}.id", self::$postExcludeIds])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(3)
            ->all();

        foreach ($videoReviewNews as $post) {
            self::$postExcludeIds[] = $post->id;
        }

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
        $postTable = Post::tableName();
        $assetTable = Asset::tableName();
        
        // Photo review
        $query = Post::find()
            ->innerJoin($assetTable, "{$assetTable}.assetable_id = {$postTable}.id")
            ->where([
                'is_public' => 1, 
                'with_photo' => 1,
                'content_category_id' => Post::CATEGORY_NEWS,
                "{$assetTable}.assetable_type" => Asset::ASSETABLE_POST,
                "{$assetTable}.thumbnail" => Asset::THUMBNAIL_BIG,
            ]);
        $photoReviewNews = $query->andWhere(['not in', "{$postTable}.id", self::$postExcludeIds])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(3)
            ->all();

        foreach ($photoReviewNews as $post) {
            self::$postExcludeIds[] = $post->id;
        }

        if(count($photoReviewNews) == 0) {
            return false;
        }

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
        $query = Post::find()
            ->innerJoin($assetTable, "{$assetTable}.assetable_id = {$postTable}.id")
            ->where([
                'is_public' => 1, 
                'with_video' => 1,
                'content_category_id' => Post::CATEGORY_NEWS,
                "{$assetTable}.assetable_type" => Asset::ASSETABLE_POST,
                "{$assetTable}.thumbnail" => Asset::THUMBNAIL_BIG,
            ]);
        $videoReviewNews = $query->andWhere(['not in', "{$postTable}.id", self::$postExcludeIds])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(3)
            ->all();

        foreach ($videoReviewNews as $post) {
            self::$postExcludeIds[] = $post->id;
        }

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

    /**
     * Get question block
     * @param \common\models\Question $question 
     * @return array Data
     */
    public static function getQuestionBlock($question = false)
    {
        if(!$question) {
            $question = Question::find()
                ->where(['is_active' => 1])
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

        $query = Question::find()->where(['parent_id' => $question->id]);
        if($block) $query->orderBy(['voutes' => SORT_DESC, 'mark' => SORT_DESC]);
        $answers = $query->all();

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

}