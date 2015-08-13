<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\user\models\User;
use common\modules\user\models\Profile;
use yii\helpers\Url;
use yii\data\Pagination;

/**
 * This is the model class for table "comments".
 *
 * @property integer $id
 * @property string $content
 * @property string $created_at
 * @property integer $commentable_id
 * @property string $commentable_type
 * @property integer $user_id
 * @property integer $parent_id
 *
 * @property Users $user
 * @property Comment $parent
 * @property Comment[] $comments
 */
class Comment extends ActiveRecord
{
    /**
     * @var string commentable types
     */
    const COMMENTABLE_ALBUM    = 'album';
    const COMMENTABLE_MATCH    = 'match';
    const COMMENTABLE_PHOTO    = 'photo';
    const COMMENTABLE_POST     = 'post';
    const COMMENTABLE_TRANSFER = 'transfer';
    const COMMENTABLE_VIDEO    = 'video';
    const COMMENTABLE_INQUIRER = 'inquirer';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['created_at'], 'safe'],
            [['commentable_id', 'user_id', 'parent_id'], 'integer'],
            [['commentable_type'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Содержимое',
            'created_at' => 'Создано',
            'commentable_id' => 'ID сущности',
            'commentable_type' => 'Тип материала',
            'user_id' => 'Пользователь',
            'parent_id' => 'Родительский ID',
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
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->content = nl2br($this->content);
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        switch ($this->commentable_type) {
            case self::COMMENTABLE_POST:
            case self::COMMENTABLE_TRANSFER:
            case self::COMMENTABLE_MATCH:
            case self::COMMENTABLE_VIDEO:
            case self::COMMENTABLE_INQUIRER:
                $commentCount = CommentCount::find()
                    ->where([
                        'commentable_id' => $this->commentable_id,
                        'commentable_type' => $this->commentable_type,
                    ])->one();
                if(empty($commentCount->id)) {
                    $commentCount = new CommentCount();
                    $commentCount->commentable_id = $this->commentable_id;
                    $commentCount->commentable_type = $this->commentable_type;
                }
                $count = Comment::find()
                    ->where([
                        'commentable_type' => $this->commentable_type,
                        'commentable_id' => $this->commentable_id,
                    ])->count();
                $commentCount->count = $count;
                $commentCount->save(false);
                break;
            case self::COMMENTABLE_PHOTO:
            case self::COMMENTABLE_ALBUM:
                if($this->commentable_type == self::COMMENTABLE_ALBUM) {
                    $albumID = $this->commentable_id;
                } else {
                    $albumTable = Album::tableName();
                    $assetTable = Asset::tableName();
                    $album = (new \yii\db\Query())
                        ->select("{$albumTable}.id")
                        ->from($albumTable)
                        ->innerJoin($assetTable, "{$assetTable}.assetable_id = {$albumTable}.id")
                        ->where([
                            "{$assetTable}.id" => $this->commentable_id,
                            "{$assetTable}.assetable_type" => Asset::ASSETABLE_ALBUM,
                        ])
                        ->one();
                    if(isset($album['id'])) {
                        $albumID = $album['id'];
                    }
                }
                if(isset($albumID)) {
                    $commentCount = CommentCount::find()
                        ->where([
                            'commentable_id' => $albumID,
                            'commentable_type' => self::COMMENTABLE_ALBUM,
                        ])->one();
                    if(empty($commentCount->id)) {
                        $commentCount = new CommentCount();
                        $commentCount->commentable_id = $albumID;
                        $commentCount->commentable_type = self::COMMENTABLE_ALBUM;
                        $commentCount->count = 0;
                    }
                    // $commentCount->count++;
                    $albumCount = Comment::find()
                        ->where([
                            'commentable_type' => $this->commentable_type,
                            'commentable_id' => $this->commentable_id,
                        ])->count();

                    $assetTable = Asset::tableName();
                    $albumTable = self::tableName();
                    $ids = (new \yii\db\Query())
                        ->select("{$assetTable}.id")
                        ->from($assetTable)
                        ->innerJoin($albumTable, "{$albumTable}.id = {$assetTable}.assetable_id")
                        ->where([
                            "{$albumTable}.id" => $albumID,
                            "{$assetTable}.assetable_type" => Asset::ASSETABLE_ALBUM,
                        ])
                        ->all();
                    $assetIDs = [];
                    foreach ($ids as $id) {
                        $assetIDs[] = (int) $id['id'];
                    }

                    $assetsCount = Comment::find()
                        ->where([
                            'commentable_type' => self::COMMENTABLE_PHOTO,
                            'commentable_id' => $assetIDs,
                        ])->count();

                    $commentCount->count = $albumCount + $assetsCount;
                    $commentCount->save(false);
                }
                break;
            default:
                break;
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Get comment content
     *
     * @return string
     */
    public function getContent()
    {
        return strip_tags($this->content, '<br><p>');
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
     * Get the link to commentable entity
     *
     * @return string
     */
    public function getCommentableLink()
    {
        return yii\helpers\Html::a($this->getCommentableType().'/'.$this->commentable_id,[$this->getCommentableUrl()]);
    }

    /**
     * Get the url to commentable entity
     *
     * @return string
     */
    public function getCommentableUrl()
    {
        return yii\helpers\Url::to('/'.$this->getCommentableType().'/'.$this->commentable_id);
    }

    /**
     * Get the url to commentable entity
     *
     * @return string
     */
    public function getCommentableType()
    {
        return strtolower($this->commentable_type);
    }

    /**
     * Get rating 
     * 
     * @return integer 
     */
    public function getRating()
    {
        return Vote::getRating($this->id, Vote::VOTEABLE_COMMENT);
    }

    /**
     * Get user vote for comment
     * 
     * @return integer 
     */
    public function getUserVote()
    {
        return Vote::getUserVote($this->id, Vote::VOTEABLE_COMMENT);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Comment::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['parent_id' => 'id']);
    }
    
    /**
     * Get data for comments block
     * @param int $entityId Commentable id
     * @param int $entityType Commentable type
     * @return array
     */
    public static function getCommentsBlock($entityId, $entityType)
    {
        $commentForm = new CommentForm();
        $commentForm->commentable_id = $entityId;
        $commentForm->commentable_type = $entityType;

        // out comments with pagination
        $commentsCount = Comment::find()
            ->where([
                'commentable_id' => $entityId,
                'commentable_type' => $entityType,
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
                'commentable_id' => $entityId,
                'commentable_type' => $entityType,
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

        $block = [
            'view' => '@frontend/views/blocks/comments_block',
            'data' => [
                'comments' => $sortedComments,
                'commentForm' => $commentForm,
                'pagination' => $commentsPagination,
            ],
        ];
        return $block;
    }

    /**
     * Output tree of comments
     * @param array $comments Array of Comment
     * @param int $parent_id 
     * @param array $options 
     */
    public static function outCommentsTree($comments, $parent_id, $options)
    {
        if(!isset($options) || !is_object($options))
        {
            $showReplies = isset($options['showReplies']) ? $options['showReplies'] : true;
            $showReplyButton = isset($options['showReplyButton']) ? $options['showReplyButton'] : true;
            $postID = isset($options['postID']) ? $options['postID'] : false;
            $options = (object) compact('showReplies','showReplyButton','postID');
        }
        if (isset($comments[$parent_id])) 
        { 
            foreach ($comments[$parent_id] as $comment) 
            {
                if(is_null($comment->user)) {
                    $username = 'Аноним';
                    $avatar = new Asset();
                    $avatar->assetable_type = Asset::ASSETABLE_USER;
                    $imageUrl = $avatar->getDefaultFileUrl();
                    $userUrl = false;
                } else {
                    $username = $comment->user->getDisplayName();
                    $avatar = $comment->user->getAsset();
                    $imageUrl = $avatar->getFileUrl();
                    $userUrl = $comment->user->getUrl();
                }

                $commentDate = Yii::$app->formatter->asDate(strtotime($comment->created_at), 'd MMMM Y HH:mm');

                $repliesCommentsCount = isset($comments[$comment->id]) ? count($comments[$comment->id]) : 0;
                $classRepliesCount = ($repliesCommentsCount == 0) ? 'no-replies' : '';
                $textRepliesCount = ($repliesCommentsCount == 0) ? '' : $repliesCommentsCount;
                $isReply = $parent_id == 0 ? false : true;
                $own = isset(Yii::$app->user->id) && Yii::$app->user->id == $comment->user_id ? 'yes' : 'no';

                $rating = $comment->getRating();
                $ratingUpClass = '';
                $ratingDownClass = '';
                if(!Yii::$app->user->isGuest && Yii::$app->user->id != $comment->user_id)
                {
                    $userRating = $comment->getUserVote();
                    if($userRating == 1) {
                        $ratingUpClass = 'voted';
                    } elseif ($userRating == -1) {
                        $ratingDownClass = 'voted';
                    }
                } else {
                    $ratingUpClass = 'disable';
                    $ratingDownClass = 'disable';
                }
                $commentLevelClass = $parent_id == 0 ? 'lvl-one' : '';

                if($parent_id == 0 && $options->postID && $comment->getCommentableType() == Comment::COMMENTABLE_POST) 
                {
                    $post = Post::findOne($comment->commentable_id);
                    if (isset($post->id) && $post->id !== $options->postID)
                    {
                        $options->postID = $post->id; 
                        ?>
                        <div class="comment-theme">
                            <div class="theme-label">Комментарии по теме:</div>
                            <div class="theme-link">
                                <a href="<?= $post->getUrl() ?>" data-pjax="0"><?= $post->title ?></a>
                            </div>
                        </div>
                        <?php 
                    }
                } 
                $adminLink = '';
                if(Yii::$app->user->can('admin')) {
                  $adminLink = '<a class="admin-view-link" target="_blank" href="/admin/comment/'.$comment->id.'" data-pjax="0"></a>';
                } 
                ?>
                <div id="comment-<?= $comment->id ?>" class="comment <?= $commentLevelClass ?>" 
                    data-own="<?= $own ?>"
                    data-comment-id="<?= $comment->id ?>"
                    data-commentable-type="<?= $comment->getCommentableType() ?>"
                    data-commentable-id="<?= $comment->commentable_id ?>" >
                    <div class="comment-user">
                        <div class="user-photo">
                            <?php if($userUrl) { ?>
                                <a href="<?= $userUrl ?>" data-pjax="0">
                            <?php } ?>
                                <img src="<?= $imageUrl ?>">
                            <?php if($userUrl) { ?>
                                </a>
                            <?php } ?>

                        </div>
                        <div class="user-info">
                            <div class="user-name">
                                <?php if($userUrl) { ?>
                                    <a href="<?= $userUrl ?>" data-pjax="0">
                                <?php } ?>
                                    <?= $username ?>
                                <?php if($userUrl) { ?>
                                    </a>
                                <?php } ?>
                            </div>
                            <div class="post-time"><?= $commentDate ?></div>
                        </div>
                    </div>
                    <div class="comment-links">
                        <div class="rating-counter">
                            <a href="javascript:void(0)" class="rating-up <?= $ratingUpClass ?>" data-id="<?= $comment->id ?>" data-type="comment"></a>
                            <div class="rating-count <?=($rating >= 0) ? 'blue' : 'red'?>"><?=$rating?></div>
                            <a href="javascript:void(0)" class="rating-down <?= $ratingDownClass ?>" data-id="<?= $comment->id ?>" data-type="comment"></a>
                        </div>
                        <?php if(!Yii::$app->user->isGuest) { ?>
                            <a href="<?= Url::to('/complain/'.$comment->id) ?>" class="button-complain" title="Пожаловаться" data-pjax="0"></a>
                        <?php } ?>
                        <?php if(!Yii::$app->user->isGuest && $options->showReplyButton) { ?>
                            <a href="javascript:void(0)" class="button-reply" title="Ответить"></a>
                        <?php } ?>
                        <?= $adminLink ?>
                    </div>
                    <div class="comment-body">
                        <?= $comment->getContent() ?>
                    </div>
                    <?php if($repliesCommentsCount > 0) { ?>
                    <div class="comment-replies">
                        <a class="replies-toggle-btn toggle-button toggle-<?= ($options->showReplies) ? 'hide' : 'show' ?>" data-target="comment-replies-content-<?= $comment->id ?>" href="javascript:void(0)">
                            <div class="toggle-text">
                                <span><?= ($options->showReplies) ? 'Скрыть' : 'Показать' ?></span> ответы
                            </div>
                            <div class="toggle-icon"></div>
                        </a>
                        <div id="comment-replies-content-<?= $comment->id ?>" class="toggle-content <?= ($options->showReplies) ? 'visible' : '' ?>">
                        <?php
                            self::outCommentsTree($comments, $comment->id, $options);
                        ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <?php
                
            }
        }
    }
}
