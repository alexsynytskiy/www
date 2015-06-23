<?php

namespace common\models;

use Yii;
use amnah\yii2\user\models\User;
use common\models\Team;

/**
 *
 */
class SiteBlock
{
    static $postExcludeIds = [];

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

        $block = [
            'view' => '@frontend/views/blocks/review_news_block',
            'data' => compact('photoReviewNews','videoReviewNews'),
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

        $answers = Question::find()
            ->where(['parent_id' => $question->id])
            ->all();

        $uid = isset(Yii::$app->user->id) ? Yii::$app->user->id : 0;
        $userVote = QuestionVote::find()
            ->where([
                'question_id' => $question->id,
                'user_id' => $uid,
            ])->one();
        if(isset($userVote->id) || !$question->is_active){
            $view = '@frontend/views/blocks/question_block';
        } else {
            $view = '@frontend/views/forms/question_form';
        }

        $block = [
            'view' => $view,
            'data' => compact('question', 'answers'),
        ];
        return $block;
    }

}