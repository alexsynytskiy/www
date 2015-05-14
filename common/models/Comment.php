<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use amnah\yii2\user\models\User;
use yii\helpers\Url;

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
    const COMMENTABLE_MATCH    = 'match';
    const COMMENTABLE_PHOTO    = 'photo';
    const COMMENTABLE_POST     = 'post';
    const COMMENTABLE_TRANSFER = 'transfer';

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
            'commentable_type' => 'Тип сущности',
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

    public function afterSave($insert, $changedAttributes)
    {
        switch ($this->commentable_type) {
            case self::COMMENTABLE_POST:
                $post = Post::findOne($this->commentable_id);
                if(!empty($post->id))
                {
                    $count = Comment::find()
                        ->where([
                            'commentable_type' => self::COMMENTABLE_POST,
                            'commentable_id' => $post->id,
                        ])->count();
                    $post->comments_count = $count;
                    $post->save(false);
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
        return strip_tags($this->content);
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
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
     * Output tree of comments
     * @param array $comments Array of Comment
     * @param int $parent_id 
     * @param int $level 
     */
    public static function outCommentsTree($comments, $parent_id, $level, $showReplies = true) 
    {
        if (isset($comments[$parent_id])) 
        { 
            foreach ($comments[$parent_id] as $comment) 
            {
                // header('Content-Type: text/html; charset=utf-8');
                // var_dump($comment);
                // die;
                $username = $comment->user->getDisplayName();
                $avatar = $comment->user->getAsset();
                $imageUrl = $avatar->getFileUrl();

                $commentDate = Yii::$app->formatter->asDate($comment->created_at, 'dd MMMM YYYY HH:mm');

                $repliesCommentsCount = isset($comments[$comment->id]) ? count($comments[$comment->id]) : 0;
                $classRepliesCount = ($repliesCommentsCount == 0) ? 'no-replies' : '';
                $textRepliesCount = ($repliesCommentsCount == 0) ? '' : $repliesCommentsCount;
                $isReply = $parent_id == 0 ? false : true;

                $rating = 5;
                $displayType = 'comment';
                $page = 'post';

                ?>
                <div id="comment-<?= $comment->id ?>" class="comment">
                    <div class="comment-user">
                        <div class="user-photo"><a href="<?= Url::to('/user/profile/'.$comment->user->id) ?>"><img src="<?=$imageUrl?>"></a></div>
                        <div class="user-info">
                            <div class="user-name"><a href="<?= Url::to('/user/profile/'.$comment->user->id) ?>"><?=$username?></a></div>
                            <div class="post-time"><?= $commentDate ?></div>
                        </div>
                    </div>
                    <div class="comment-links">
                        <div class="rating-counter">
                            <a href="javascript:void(0)" class="rating-up"></a>
                            <div class="rating-count <?=($isReply)?'blue':'red'?>"><?=$rating?></div>
                            <a href="javascript:void(0)" class="rating-down"></a>
                        </div>
                        <?php if($displayType == 'comment'): ?>
                            <?php if(!Yii::$app->user->isGuest) { ?>
                            <a href="javascript:void(0)" class="button-reply" title="Ответить" data-comment-id="<?= $comment->id ?>"></a>
                            <?php } ?>
                            <?php if($page == 'cabinet') { ?>
                                <a href="javascript:void(0)" class="new-replies-count <?=$classRepliesCount?>" title="Новых ответов">
                                    <?=$textRepliesCount?>
                                </a>
                            <?php } ?>
                        <?php else: ?>
                            <a href="javascript:void(0)" class="button-edit" title="Изменить"></a>
                            <a href="javascript:void(0)" class="button-remove" title="Удалить"></a>
                        <?php endif; ?>
                    </div>
                    <?php if($displayType == 'post') { ?>
                        <a href="#" class="post-title">
                            <?php // echo $post->title; ?>
                        </a>
                    <?php } ?>
                    <div class="comment-body">
                        <?= $comment->getContent() ?>
                    </div>
                    <?php if($repliesCommentsCount > 0) { ?>
                    <div class="comment-replies">
                        <a class="replies-toggle-btn toggle-button toggle-<?= ($showReplies) ? 'hide' : 'show' ?>" data-target="comment-replies-content-<?= $comment->id ?>" href="javascript:void(0)">
                            <div class="toggle-text">
                                <span><?= ($showReplies) ? 'Скрыть' : 'Показать' ?></span> ответы
                            </div>
                            <div class="toggle-icon"></div>
                        </a>
                        <div id="comment-replies-content-<?= $comment->id ?>" class="toggle-content <?= ($showReplies) ? 'visible' : '' ?>">
                        <?php
                            $level++;
                            self::outCommentsTree($comments, $comment->id, $level);
                            $level--;
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
