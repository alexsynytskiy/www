<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\user\models\User;
use dosamigos\transliterator\TransliteratorHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "posts".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string  $title
 * @property string  $slug
 * @property string  $content
 * @property integer $is_public
 * @property string  $created_at
 * @property string  $updated_at
 * @property integer $is_index
 * @property integer $is_top
 * @property integer $is_pin
 * @property integer $with_video
 * @property integer $with_photo
 * @property integer $content_category_id
 * @property string  $source_title
 * @property string  $source_url
 * @property integer $is_yandex_rss
 * @property integer $is_vk_rss
 * @property integer $is_fb_rss
 * @property integer $is_tw_rss
 * @property string  $cached_tag_list
 * @property integer $allow_comment
 *
 * @property Users $user
 */
class Post extends ActiveRecord
{

    /**
     * @var int content_category_id for news
     */
    const CATEGORY_NEWS = 1;
    /**
     * @var int content_category_id for blog
     */
    const CATEGORY_BLOG = 2;

    /**
     * @var Asset
     */
    public $image;

    /**
     * @var string Tags
     */
    public $tags;

    /**
     * @var integer Source id
     */
    public $source_id;

    /**
     * @var boolean Selected blog state
     */
    public $selected_blog;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'is_public', 'is_index', 'is_top', 'is_pin', 
                'with_video', 'with_photo', 'content_category_id', 
                'is_yandex_rss', 'allow_comment', 'source_id',
                'is_vk_rss', 'is_fb_rss', 'is_tw_rss'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at', 'tags', 'selected_blog'], 'safe'],
            [['title', 'slug', 'source_title', 'source_url', 'cached_tag_list'], 'string', 'max' => 255],

            //required
            [['title', 'content', 'content_category_id'], 'required'],

            // image
            [['image'], 'file', 'extensions' => 'jpeg, jpg, gif, png'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                  => 'ID записи',
            'user_id'             => 'Автор',
            'title'               => 'Заголовок',
            'slug'                => 'URL псевдоним',
            'content'             => 'Содержимое',
            'is_public'           => 'Опубликовано',
            'created_at'          => 'Создано',
            'updated_at'          => 'Обновлено',
            'with_video'          => 'С видео',
            'with_photo'          => 'С фото',
            'is_index'            => 'Топ 3',
            'is_top'              => 'Топ 6',
            'is_pin'              => 'Выделить в ленте',
            'content_category_id' => 'Категория',
            'source_title'        => 'Название источника',
            'source_url'          => 'Адрес источника',
            'is_yandex_rss'       => 'Яндекс RSS',
            'is_vk_rss'           => 'Vk RSS',
            'is_fb_rss'           => 'Facebook RSS',
            'is_tw_rss'           => 'Twitter RSS',
            'cached_tag_list'     => 'Закешированный список тегов',
            'allow_comment'       => 'Можно комментировать',
            'image'               => 'Изображение',
            'tags'                => 'Теги',
            'source_id'           => 'Источник',
            'selected_blog'       => 'Избранный блог',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class'      => 'yii\behaviors\TimestampBehavior',
                'value'      => function () { return date("Y-m-d H:i:s"); },
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
            ],
        ];
    }

    /**
     * After save and before delete update cache of blocks
     */
    public function updateCacheBlocks($changedAttributes)
    {
        $newsPosts50 = Post::find()
            ->where(['is_public' => 1, 'content_category_id' => Post::CATEGORY_NEWS])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(50)
            ->all();
        $newsPosts20 = array_slice($newsPosts50, 0, 20);

        $cacheBlocksData = CacheBlock::find()->all();
        $cacheBlocks = [];
        foreach ($cacheBlocksData as $block) {
            $cacheBlocks[$block->machine_name] = $block;
        }

        $cacheStatus = false;

        if(isset($changedAttributes['content_category_id']) &&
            $changedAttributes['content_category_id'] == self::CATEGORY_NEWS &&
            $this->content_category_id != self::CATEGORY_NEWS ||
            $this->content_category_id == self::CATEGORY_NEWS) // news posts
        {
            $machineName = 'shortNews50';
            $enableBanners = false;
            if(!isset($cacheBlocks[$machineName])){
                $cacheBlock = new CacheBlock();
                $cacheBlock->machine_name = $machineName;
            } else {
                $cacheBlock = $cacheBlocks[$machineName];
            }
            $cacheBlock->content = SiteBlock::getShortNews(50, $enableBanners, $cacheStatus, $newsPosts50);
            $cacheBlock->save();

            $machineName = 'shortNews50banners';
            $enableBanners = true;
            SiteBlock::$postedBannerIds = [];
            if(!isset($cacheBlocks[$machineName])){
                $cacheBlock = new CacheBlock();
                $cacheBlock->machine_name = $machineName;
            } else {
                $cacheBlock = $cacheBlocks[$machineName];
            }
            $cacheBlock->content = SiteBlock::getShortNews(50, $enableBanners, $cacheStatus, $newsPosts50);
            $cacheBlock->save();
        }

        if(isset($changedAttributes['is_index']) && $this->is_index != $changedAttributes['is_index'] ||
            isset($changedAttributes['is_top']) && $this->is_top != $changedAttributes['is_top'] ||
            $this->is_top || $this->is_index)
        {
            $machineName = 'top3News';
            if(!isset($cacheBlocks[$machineName])){
                $cacheBlock = new CacheBlock();
                $cacheBlock->machine_name = $machineName;
            } else {
                $cacheBlock = $cacheBlocks[$machineName];
            }
            $cacheBlock->content = SiteBlock::getTop3News($cacheStatus);
            $cacheBlock->save();
        
            $machineName = 'top6News';
            if(!isset($cacheBlocks[$machineName])){
                $cacheBlock = new CacheBlock();
                $cacheBlock->machine_name = $machineName;
            } else {
                $cacheBlock = $cacheBlocks[$machineName];
            }
            $cacheBlock->content = SiteBlock::getTop6News($cacheStatus);
            $cacheBlock->save();
        }

        if($this->content_category_id == self::CATEGORY_BLOG) // blog posts
        {
            $machineName = 'lastBlogPosts';
            if(!isset($cacheBlocks[$machineName])){
                $cacheBlock = new CacheBlock();
                $cacheBlock->machine_name = $machineName;
            } else {
                $cacheBlock = $cacheBlocks[$machineName];
            }
            $cacheBlock->content = SiteBlock::getBlogPosts($cacheStatus);
            $cacheBlock->save();

            $machineName = 'blogPostsByRating';
            if(!isset($cacheBlocks[$machineName])){
                $cacheBlock = new CacheBlock();
                $cacheBlock->machine_name = $machineName;
            } else {
                $cacheBlock = $cacheBlocks[$machineName];
            }
            $cacheBlock->content = SiteBlock::getBlogPostsByRating($cacheStatus);
            $cacheBlock->save();
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // source
            if(isset($this->source_id) && $this->source_id > 0) {
                $source = Source::findOne($this->source_id);
                if(isset($source)) {
                    $this->source_title = $source->name;
                    $this->source_url = $source->url;
                }
            }
            // slug
            $this->slug = $this->genSlug($this->title);
            // created_at
            $this->created_at = date('Y-m-d H:i:s', strtotime($this->created_at));
            // content
//            $pattern = '~<a .*href=".*" .*>(.*)</a>~U';
//            $this->content = preg_replace($pattern, '$1', $this->content);
            // Selected blogs
            if(isset($this->selected_blog)) 
            {
                if($this->selected_blog) {
                    $selectedBlog = SelectedBlog::find()->where(['post_id' => $this->id])->one();
                    if(!isset($selectedBlog)) {
                        $selectedBlog = new SelectedBlog();
                        $selectedBlog->post_id = $this->id;
                        $selectedBlog->save(false);
                    }
                } else {
                    SelectedBlog::deleteAll(['post_id' => $this->id]);
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->updateCacheBlocks($changedAttributes);
        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        $this->updateCacheBlocks();
        Tagging::deleteAll(['taggable_type' => Tagging::TAGGABLE_POST ,'taggable_id' => $this->id]);
        Relation::deleteAll(['relationable_type' => Relation::RELATIONABLE_POST ,'relationable_id' => $this->id]);
        Comment::deleteAll(['commentable_type' => Comment::COMMENTABLE_POST ,'commentable_id' => $this->id]);
        CommentCount::deleteAll(['commentable_type' => CommentCount::COMMENTABLE_POST ,'commentable_id' => $this->id]);
        SelectedBlog::deleteAll(['post_id' => $this->id]);
        $assets = Asset::find()->where(['assetable_type' => Asset::ASSETABLE_POST ,'assetable_id' => $this->id])->all();
        foreach ($assets as $asset) {
            $asset->delete();
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Get author's username
     *
     * @return string
     */
    public function getUserName(){
        return $this->user->username;
    }


    /**
     * Get list of categories for creating dropdowns
     *
     * @return array Array of string
     */
    public static function categoryDropdown()
    {
        static $dropdown;
        if ($dropdown === null) {

            $dropdown[self::CATEGORY_BLOG] = self::categoryHumanName(self::CATEGORY_BLOG);
            $dropdown[self::CATEGORY_NEWS] = self::categoryHumanName(self::CATEGORY_NEWS);

        }
        return $dropdown;
    }

    /**
     * Get category human name
     *
     * @param int $category_id
     * @return string
     */
    public static function categoryHumanName($category_id)
    {
        if ($category_id == self::CATEGORY_BLOG) return 'Блог';
        elseif ($category_id == self::CATEGORY_NEWS) return 'Новости';

        return 'Не определено';
    }

    /**
     * Get current category
     *
     * @return string
     */
    public function getCategory()
    {
        return self::categoryHumanName($this->content_category_id);
    }
    
    /**
     * Get short content
     *
     * @return string
     */
    public function getShortContent($min = 200, $max = 350)
    {
        $content = $this->content;
        $content = strip_tags($content);
        if(mb_strlen($content,'UTF-8') <= $min)
        {
            return $content;          
        }
        $cutLength = mb_strpos($content,'. ', $min, 'UTF-8');
        if($cutLength && $cutLength < $max) 
        {
            $content = mb_substr($content, 0, $cutLength, 'UTF-8');
        } 
        else 
        {
            $content = mb_substr($content, 0, $min, 'UTF-8');
            $content = trim($content);
        }
        $content .= '...';
        return $content;
    }

    /**
     * @return string Url to post
     */
    public function getUrl()
    {
        if($this->content_category_id == self::CATEGORY_NEWS) {
            return Url::to('/news/'.$this->id.'-'.$this->slug);
        } else {
            return Url::to('/blog/'.$this->id.'-'.$this->slug);
        }
    }

    /**
     * @return array Array of Asset
     */
    public function getAssets()
    {
        return Asset::getAssets($this->id, Asset::ASSETABLE_POST, NULL);
    }

    /**
     * Get single asset
     *
     * @param string $thumbnail
     *
     * @return Asset
     */
    public function getAsset($thumbnail = NULL)
    {
        return Asset::getAssets($this->id, Asset::ASSETABLE_POST, $thumbnail, true);
    }

    /**
     * @return array Array of Tag
     */
    public function getTags()
    {
        $tagging = Tagging::find()
            ->where([
                'taggable_id' => $this->id,
                'taggable_type' => Tagging::TAGGABLE_POST,
            ])
            ->asArray()
            ->all();
        $tags = [];
        foreach ($tagging as $data) {
            $tags[] = Tag::findOne($data['tag_id']);
        }
        return $tags;
    }

    /**
     * @param int $id Tag id
     *
     * @return boolean
     */
    public function removeTag($id)
    {
        $tagging = Tagging::find()
            ->where([
                'taggable_id' => $this->id,
                'tag_id' => $id,
                'taggable_type' => Tagging::TAGGABLE_POST,
            ])->one();
        if($tagging) return $tagging->delete();
        return false;
    }

    /**
     * @param mixed $id Tag id OR new tag name
     *
     * @return boolean
     */
    public function addTag($id)
    {
        if(is_numeric($id))
        {
            $tag = Tag::find()->where(['id' => $id])->one();
        }
        elseif(strpos($id,'{new}') !== false)
        {
            $name = mb_substr($id, 5, mb_strlen($id,'UTF-8') - 5,'UTF-8');
            $tag = new Tag();
            $tag->name = $name;
            if(!$tag->save()) return false;
        }

        if(!empty($tag)) {
            $tagging = new Tagging();
            $tagging->taggable_id = $this->id;
            $tagging->taggable_type = Tagging::TAGGABLE_POST;
            $tagging->tag_id = $tag->id;
            return $tagging->save();
        }
        return false;
    }

    /**
     * @param string $title Text to transliteration
     *
     * @return string
     */
    public function genSlug($title)
    {
        $slug = trim($title);
        $slug = str_replace(["я", "Я"], "ya", $slug);
        $slug = str_replace(["ю", "Ю"], "yu", $slug);
        $slug = str_replace(["ш", "Ш"], "sh", $slug);
        $slug = str_replace(["щ", "Щ"], "sch", $slug);
        $slug = str_replace(["ж", "Ж"], "zh", $slug);
        $slug = str_replace(["ч", "Ч"], "ch", $slug);
        $slug = TransliteratorHelper::process($slug, '-', 'en');
        $slug = str_replace(["ʹ",'?','.',',','@','!','#','$','%','^','&','*','(',')','{','}','[',']','+',':',';','"',"'",'`','~','\\','/','|','№'], "", $slug);
        $slug = str_replace(" ", "-", $slug);
        $slug = strtolower($slug);
        return $slug;
    }

    /**
     * Initialize hierachy of comments to post
     * 
     * @return array Array of common\models\Comment
     */
    public function getComments() 
    {
        $comments = Comment::find()
            ->where([
                'commentable_type' => Comment::COMMENTABLE_POST,
                'commentable_id' => $this->id,
            ])
            ->all();

        $sortedComments = [];
        foreach ($comments as $comment) 
        {
            $index = $comment->parent_id == null ? 0 : $comment->parent_id;
            $sortedComments[$index][] = $comment;
        }
        return $sortedComments;
    }

    /**
     * Get amount of photos in album
     * @return int
     */
    public function getCommentsCount() {
        return CommentCount::getCommentCount($this->id, CommentCount::COMMENTABLE_POST);
    }

    /**
     * Get rating
     * 
     * @return integer 
     */
    public function getRating()
    {
        return Vote::getRating($this->id, Vote::VOTEABLE_POST);
    }

    /**
     * Get user vote for comment
     * 
     * @return integer 
     */
    public function getUserVote()
    {
        return Vote::getUserVote($this->id, Vote::VOTEABLE_POST);
    }

    /**
     * Check type model of post if it blog
     * 
     * @return boolean 
     */
    public function isBlog() {
        return $this->content_category_id == self::CATEGORY_BLOG;
    }

    /**
     * Check type model of post if it blog
     * 
     * @return boolean 
     */
    public function isSelected() {

        $selectedBlogs = SelectedBlog::find()
            ->where(['post_id' => $this->id])
            ->one();

        return isset($selectedBlogs);
    }

}
