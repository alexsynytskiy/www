<?php

namespace common\modules\user\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use yii\data\Pagination;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

use common\models\Post;
use common\models\Asset;
use common\models\Comment;
use common\models\CommentForm;
use common\models\SiteBlock;
use common\modules\user\models\User;

/**
 * Default controller for User module
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'confirm', 'resend'],
                        'allow'   => true,
                        'roles'   => ['?', '@'],
                    ],
                    [
                        'actions' => ['account', 'profile', 'resend-change', 'cancel', 'logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                    [
                        'actions' => ['forgot', 'reset'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => ['register', 'login'],
                        'allow'   => true,
                        'roles'   => ['?'],
                        'matchCallback' => function ($rule, $action) {
                            if(User::hasBannedIP()) {
                                throw new ForbiddenHttpException('Ваш IP адрес забанен.');
                            }
                            return true;
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    // 'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Display index - debug page, login page, or account page
     */
    public function actionIndex()
    {
        if (defined('YII_DEBUG') && YII_DEBUG) {
            $actions = Yii::$app->getModule("user")->getActions();
            return $this->render('index', ["actions" => $actions]);
        } elseif (Yii::$app->user->isGuest) {
            return $this->redirect(["/user/login"]);
        } else {
            return $this->redirect(["/user/account"]);
        }
    }

    /**
     * Display login page
     */
    public function actionLogin()
    {
        /** @var \common\modules\user\models\forms\LoginForm $model */

        // load post data and login
        $user = Yii::$app->getModule("user")->model("LoginForm");
        if ($user->load(Yii::$app->request->post()) && $user->login(Yii::$app->getModule("user")->loginDuration)) {
            return $this->redirect('/');
        }

        // backend render
        if( Yii::getAlias('@app') == Yii::getAlias('@backend')) {
            return $this->render('/backend/login', [
                'user' => $user,
            ]);
        }

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col3',
            'title' => Yii::t('user','Вход'),
            'columnFirst' => [
                'top3News' => SiteBlock::getTop3News(),
                'top6News' => SiteBlock::getTop6News(),
                'subscribing' => SiteBlock::getSubscribingForm(),
                'blog_column' => SiteBlock::getBlogPosts(),
            ],
            'columnSecond' => [
                'login_block' => [
                    'view' => '@frontend/views/blocks/login_block',
                    'data' => compact('user'),
                ],
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
     * Log user out and redirect
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        // redirect
        $logoutRedirect = Yii::$app->getModule("user")->logoutRedirect;
        if ($logoutRedirect === null) {
            return $this->goHome();
        }
        else {
            return $this->redirect($logoutRedirect);
        }
    }

    /**
     * Display registration page
     */
    public function actionRegister()
    {
        /** @var \common\modules\user\models\User    $user */
        /** @var \common\modules\user\models\Profile $profile */
        /** @var \common\modules\user\models\Role    $role */

        // set up new user/profile objects
        $user    = Yii::$app->getModule("user")->model("User", ["scenario" => "register"]);
        $profile = Yii::$app->getModule("user")->model("Profile");

        // load post data
        $post = Yii::$app->request->post();
        if ($user->load($post)) {

            // Generate login
            $user->username = str_replace(['.','-','@'], '', $user->email);

            // ensure profile data gets loaded
            $profile->load($post);

            // validate for ajax request
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($user, $profile);
            }

            // validate for normal request
            if ($user->validate() && $profile->validate()) {

                // perform registration
                $role = Yii::$app->getModule("user")->model("Role");
                $user->setRegisterAttributes($role::ROLE_USER, Yii::$app->request->userIP)->save(false);
                $profile->setUser($user->id)->save(false);

                $uploadedFile = UploadedFile::getInstance($user, 'avatar');
                if(!empty($uploadedFile))
                {
                    // Save origionals 
                    $originalAsset = new Asset();
                    $originalAsset->assetable_type = Asset::ASSETABLE_USER;
                    $originalAsset->assetable_id = $user->id;
                    $originalAsset->uploadedFile = $uploadedFile;
                    $originalAsset->saveAsset();

                    // Save thumbnails 
                    $imageID = $originalAsset->id;
                    $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_USER);

                    foreach ($thumbnails as $thumbnail) {
                        $asset = new Asset();
                        $asset->assetable_type = Asset::ASSETABLE_USER;
                        $asset->assetable_id = $user->id;
                        $asset->parent_id = $imageID;
                        $asset->thumbnail = $thumbnail;
                        $asset->uploadedFile = $uploadedFile;
                        $asset->cropData = $user->cropData;
                        $asset->saveCroppedAsset();
                    }
                }

                $this->afterRegister($user);

                // set flash
                // don't use $this->refresh() because user may automatically be logged in and get 403 forbidden
                $guestText = "";
                $successText = $user->getDisplayName().', спасибо за регистрацию. '.
                    'Администратор портала просит вас подтвердить регистрацию в письме, '.
                    'отправленном на Ваш e-mail. В ближайшее время Вы получите письмо '.
                    'с инструкциями по активации Вашей учетной записи.'.
                    '<div class="blue">Спасибо за выбор портала </div>'.
                    '<div class="blue">Dynamomania.com</div>';

                Yii::$app->session->setFlash("success-register", $successText);
                if( Yii::getAlias('@app') == Yii::getAlias('@frontend')) {
                    return Yii::$app->getResponse()->redirect(\yii\helpers\Url::to('/forum-rules'));
                }
            }

        }

        // backend render
        if( Yii::getAlias('@app') == Yii::getAlias('@backend')) {
            return $this->render('/backend/register', compact('user','profile'));
        }

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col3',
            'title' => Yii::t('user','Вход'),
            'columnFirst' => [
                'top3News' => SiteBlock::getTop3News(),
                'top6News' => SiteBlock::getTop6News(),
                'subscribing' => SiteBlock::getSubscribingForm(),
                'blog_column' => SiteBlock::getBlogPosts(),
            ],
            'columnSecond' => [
                'register_block' => [
                    'view' => '@frontend/views/blocks/register_block',
                    'data' => compact('user','profile'),
                ],
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
     * Process data after registration
     *
     * @param \common\modules\user\models\User $user
     */
    protected function afterRegister($user)
    {
        /** @var \common\modules\user\models\UserKey $userKey */

        // determine userKey type to see if we need to send email
        $userKey = Yii::$app->getModule("user")->model("UserKey");
        if ($user->status == $user::STATUS_INACTIVE) {
            $userKeyType = $userKey::TYPE_EMAIL_ACTIVATE;
        } elseif ($user->status == $user::STATUS_UNCONFIRMED_EMAIL) {
            $userKeyType = $userKey::TYPE_EMAIL_CHANGE;
        } else {
            $userKeyType = null;
        }

        // check if we have a userKey type to process, or just log user in directly
        if ($userKeyType) {

            // generate userKey and send email
            $userKey = $userKey::generate($user->id, $userKeyType);
            if (!$numSent = $user->sendEmailConfirmation($userKey)) {

                // handle email error
                //Yii::$app->session->setFlash("Email-error", "Failed to send email");
            }
        } else {
            Yii::$app->user->login($user, Yii::$app->getModule("user")->loginDuration);
        }
    }

    /**
     * Confirm email
     */
    public function actionConfirm($key)
    {
        /** @var \common\modules\user\models\UserKey $userKey */
        /** @var \common\modules\user\models\User $user */

        // search for userKey
        $success = false;
        $userKey = Yii::$app->getModule("user")->model("UserKey");
        $userKey = $userKey::findActiveByKey($key, [$userKey::TYPE_EMAIL_ACTIVATE, $userKey::TYPE_EMAIL_CHANGE]);
        if ($userKey) {

            // confirm user
            $user = Yii::$app->getModule("user")->model("User");
            $user = $user::findOne($userKey->user_id);
            $user->confirm();

            // consume userKey and set success
            $userKey->consume();
            $success = $user->email;
        }

        // render
        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col3',
            'title' => 'Подтверждение регистрации',
            'columnFirst' => [
                'top3News' => SiteBlock::getTop3News(),
                'top6News' => SiteBlock::getTop6News(),
                'subscribing' => SiteBlock::getSubscribingForm(),
                'blog_column' => SiteBlock::getBlogPosts(),
            ],
            'columnSecond' => [
                'register_block' => [
                    'view' => '@frontend/views/blocks/confirm_block',
                    'data' => compact('userKey','success'),
                ],
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
     * Account
     */
    public function actionAccount()
    {
        /** @var \common\modules\user\models\User $user */
        /** @var \common\modules\user\models\UserKey $userKey */

        // set up user and load post data
        $user = Yii::$app->user->identity;
        $user->setScenario("account");
        $loadedUser = $user->load(Yii::$app->request->post());

        // set up profile and load post data
        $profile = Yii::$app->user->identity->profile;
        $loadedProfile = $profile->load(Yii::$app->request->post());

        // validate for ajax request
        if ($loadedUser && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($user);
        }

        // validate for normal request
        if ($loadedUser && $user->validate() && $loadedProfile && $profile->validate()) {

            // generate userKey and send email if user changed his email
            if (Yii::$app->getModule("user")->emailChangeConfirmation && $user->checkAndPrepEmailChange()) {

                $userKey = Yii::$app->getModule("user")->model("UserKey");
                $userKey = $userKey::generate($user->id, $userKey::TYPE_EMAIL_CHANGE);
                if (!$numSent = $user->sendEmailConfirmation($userKey)) {

                    // handle email error
                    //Yii::$app->session->setFlash("Email-error", "Failed to send email");
                }
            }

            // save avatar
            $uploadedFile = UploadedFile::getInstance($user, 'avatar');
            if(!empty($uploadedFile))
            {
                // Save origionals 
                $originalAsset = $user->getAsset();
                if(!isset($originalAsset->id)) {
                    $originalAsset = new Asset();
                }
                $originalAsset->assetable_type = Asset::ASSETABLE_USER;
                $originalAsset->assetable_id = $user->id;
                $originalAsset->uploadedFile = $uploadedFile;
                $originalAsset->saveAsset();

                // Save thumbnails 
                $imageID = $originalAsset->id;
                $thumbnails = Asset::getThumbnails(Asset::ASSETABLE_USER);

                foreach ($thumbnails as $thumbnail) {
                    $asset = $user->getAsset($thumbnail);
                    if(!isset($asset->id)) {
                        $asset = new Asset();
                    }
                    $asset->assetable_type = Asset::ASSETABLE_USER;
                    $asset->assetable_id = $user->id;
                    $asset->parent_id = $imageID;
                    $asset->thumbnail = $thumbnail;
                    $asset->uploadedFile = $uploadedFile;
                    $asset->cropData = $user->cropData;
                    $asset->saveCroppedAsset();
                }
            }
                

            // save, set flash, and refresh page
            $user->save(false);
            $profile->save(false);
            Yii::$app->session->setFlash("success-account", Yii::t("user", "Account updated"));
            if( Yii::getAlias('@app') == Yii::getAlias('@frontend')) {
                return Yii::$app->getResponse()->redirect(\yii\helpers\Url::to('/user/profile'));
            }
        }

        // render
        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => Yii::t('user','Вход'),
            'columnFirst' => [
                'profile_edit' => [
                    'view' => '@frontend/views/profile/profile_edit',
                    'data' => compact('user', 'profile'),
                ],
            ],
            'columnSecond' => [
                'blogs' => SiteBlock::getBlogPosts(), // 3 blogs
            ],
        ]);
    }

    /**
     * Profile
     */
    public function actionProfile()
    {
        /** @var \common\modules\user\models\Profile $profile */

        if( Yii::getAlias('@app') == Yii::getAlias('@backend')) {
            throw new NotFoundHttpException('Страница не найдена.');
        }

        $profile = Yii::$app->user->identity->profile;

        // blogPostsDataProvider
        $query = Post::find()->where([
            'is_public' => 1, 
            'user_id' => $profile->user->id,
            'content_category_id' => Post::CATEGORY_BLOG,
        ]);
        $query->orderBy(['created_at' => SORT_DESC]);
        $blogPostsDataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
                'pageParam' => 'bpage',
                'pageSizeParam' => 'bpsize',
            ],
        ]);

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
        $cmd->bindValue(':user_id', $profile->user->id);
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
        $cmd->bindValue(':user_id', $profile->user->id);
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
        if(count($childComments) > 0) {
            $initialComments = array_merge($initialComments, $childComments);
        }

        $parentIDs = [];
        foreach ($initialComments as $comment) {
            if($comment->parent_id != null) $parentIDs[] = $comment->parent_id;
        }

        $sortedComments = [];
        foreach ($initialComments as $comment) 
        {
            if($comment->parent_id == null
                || $comment->user_id == $profile->user->id 
                && in_array($comment->id, $parentIDs)){
                $index = 0;
            } else {
                $index = $comment->parent_id;
            }
            $sortedComments[$index][] = $comment;
        }

        $commentForm = new CommentForm();
        
        $additionalBlocks = [
            'fisrtBanner' => SiteBlock::getBanner(\common\models\Banner::REGION_FIRST_COLUMN),
            'secondBanner' => SiteBlock::getBanner(\common\models\Banner::REGION_FIRST_COLUMN),
            'photo_news' => SiteBlock::getPhotoNews(),
            'video_news' => SiteBlock::getVideoNews(),
            'subscribing' => SiteBlock::getSubscribingForm(),
            'questionBlock' => SiteBlock::getQuestionBlock(),
        ];

        // render
        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => 'Профиль',
            'columnFirst' => [
                'user_comments' => [
                    'view' => '@frontend/views/profile/user_comments',
                    'data' => [
                        'comments' => $sortedComments, 
                        'pagination' => $commentsPagination,
                        'commentForm' => $commentForm,
                    ],
                ],
                'profile' => [
                    'view' => '@frontend/views/profile/profile_view',
                    'data' => compact('profile'),
                ],
                'blog_column' => [
                    'view' => '@frontend/views/profile/blog_posts',
                    'data' => [
                        'blogPostsDataProvider' => $blogPostsDataProvider, 
                    ],
                ],
                'additional_data' => [
                    'view' => '@frontend/views/profile/additional_data',
                    'data' => [
                        'blocks' => $additionalBlocks, 
                    ],
                ],
            ],
            'columnSecond' => [
                'blogs' => SiteBlock::getBlogPosts(), // 3 blogs
            ],
        ]);
    }

    /**
     * Resend email confirmation
     */
    public function actionResend()
    {
        /** @var \common\modules\user\models\forms\ResendForm $model */

        // load post data and send email
        $model = Yii::$app->getModule("user")->model("ResendForm");
        if ($model->load(Yii::$app->request->post()) && $model->sendEmail()) {

            // set flash (which will show on the current page)
            Yii::$app->session->setFlash("success-resend", Yii::t("user", "Confirmation email resent"));
            if( Yii::getAlias('@app') == Yii::getAlias('@frontend')) {
                return Yii::$app->getResponse()->redirect(\yii\helpers\Url::to('/'));
            }
        }

        // render
        return $this->render("resend", [
            "model" => $model,
        ]);
    }

    /**
     * Resend email change confirmation
     */
    public function actionResendChange()
    {
        /** @var \common\modules\user\models\User    $user */
        /** @var \common\modules\user\models\UserKey $userKey */

        // find userKey of type email change
        $user    = Yii::$app->user->identity;
        $userKey = Yii::$app->getModule("user")->model("UserKey");
        $userKey = $userKey::findActiveByUser($user->id, $userKey::TYPE_EMAIL_CHANGE);
        if ($userKey) {

            // send email and set flash message
            $user->sendEmailConfirmation($userKey);
            Yii::$app->session->setFlash("success-resend", Yii::t("user", "Confirmation email resent"));
            if( Yii::getAlias('@app') == Yii::getAlias('@frontend')) {
                return Yii::$app->getResponse()->redirect(\yii\helpers\Url::to('/'));
            }
        }

        // redirect to account page
        return $this->redirect(["/user/account"]);
    }

    /**
     * Cancel email change
     */
    public function actionCancel()
    {
        /** @var \common\modules\user\models\User    $user */
        /** @var \common\modules\user\models\UserKey $userKey */

        // find userKey of type email change
        $user    = Yii::$app->user->identity;
        $userKey = Yii::$app->getModule("user")->model("UserKey");
        $userKey = $userKey::findActiveByUser($user->id, $userKey::TYPE_EMAIL_CHANGE);
        if ($userKey) {

            // remove `user.new_email`
            $user->new_email = null;
            $user->save(false);

            // expire userKey and set flash message
            $userKey->expire();
            Yii::$app->session->setFlash("success-cancel", Yii::t("user", "Email change cancelled"));
            if( Yii::getAlias('@app') == Yii::getAlias('@frontend')) {
                return Yii::$app->getResponse()->redirect(\yii\helpers\Url::to('/'));
            }
        }

        // go to account page
        return $this->redirect(["/user/account"]);
    }

    /**
     * Forgot password
     */
    public function actionForgot()
    {
        /** @var \common\modules\user\models\forms\ForgotForm $model */

        // load post data and send email
        $model = Yii::$app->getModule("user")->model("ForgotForm");
        if ($model->load(Yii::$app->request->post()) && $model->sendForgotEmail()) {

            // set flash (which will show on the current page)
            Yii::$app->session->setFlash("success-forgot", Yii::t("user", "Instructions to reset your password have been sent"));
            if( Yii::getAlias('@app') == Yii::getAlias('@frontend')) {
                return Yii::$app->getResponse()->redirect(\yii\helpers\Url::to('/'));
            }
        }

        // render
        if( Yii::getAlias('@app') == Yii::getAlias('@backend')) {
            return $this->render('forgot', compact('model'));
        }

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col3',
            'title' => Yii::t('user','Вход'),
            'columnFirst' => [
                'top3News' => SiteBlock::getTop3News(),
                'top6News' => SiteBlock::getTop6News(),
                'subscribing' => SiteBlock::getSubscribingForm(),
                'blog_column' => SiteBlock::getBlogPosts(),
            ],
            'columnSecond' => [
                'forgot_block' => [
                    'view' => '@frontend/views/blocks/forgot_block',
                    'data' => compact('model'),
                ],
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
     * Reset password
     */
    public function actionReset($key)
    {
        /** @var \common\modules\user\models\User    $user */
        /** @var \common\modules\user\models\UserKey $userKey */

        // check for valid userKey
        $userKey = Yii::$app->getModule("user")->model("UserKey");
        $userKey = $userKey::findActiveByKey($key, $userKey::TYPE_PASSWORD_RESET);
        if (!$userKey) {
            return $this->render('reset', ["invalidKey" => true]);
        }

        // get user and set "reset" scenario
        $success = false;
        $user = Yii::$app->getModule("user")->model("User");
        $user = $user::findOne($userKey->user_id);
        $user->setScenario("reset");

        // load post data and reset user password
        if ($user->load(Yii::$app->request->post()) && $user->save()) {

            // consume userKey and set success = true
            $userKey->consume();
            $success = true;
        }

        // render
        // return $this->render('reset', compact("user", "success"));
        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col3',
            'title' => Yii::t('user','Вход'),
            'columnFirst' => [
                'top3News' => SiteBlock::getTop3News(),
                'top6News' => SiteBlock::getTop6News(),
                'subscribing' => SiteBlock::getSubscribingForm(),
                'blog_column' => SiteBlock::getBlogPosts(),
            ],
            'columnSecond' => [
                'forgot_block' => [
                    'view' => 'reset',
                    'data' => compact('user', 'success'),
                ],
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