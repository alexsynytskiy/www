<?php

namespace common\models;

use Yii;
use amnah\yii2\user\models\User;

/**
 *
 */
class SiteBlock
{
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
}