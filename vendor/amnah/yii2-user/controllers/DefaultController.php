<?php

namespace amnah\yii2\user\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

use yii\web\UploadedFile;
use common\models\Post;
use common\models\Asset;
use common\models\Comment;

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
                        'actions' => ['login', 'register', 'forgot', 'reset'],
                        'allow'   => true,
                        'roles'   => ['?'],
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
        /** @var \amnah\yii2\user\models\forms\LoginForm $model */

        // load post data and login
        $user = Yii::$app->getModule("user")->model("LoginForm");
        if ($user->load(Yii::$app->request->post()) && $user->login(Yii::$app->getModule("user")->loginDuration)) {
            return $this->goBack(Yii::$app->getModule("user")->loginRedirect);
        }

        // backend render
        if( Yii::getAlias('@app') == Yii::getAlias('@backend')) {
            return $this->render('/backend/login', [
                'user' => $user,
            ]);
        }

        // frontend render
        $newsPosts = Post::find()
            ->where(['is_public' => 1, 'content_category_id' => Post::CATEGORY_NEWS])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(50)
            ->all();

        $blogPosts = Post::find()
            ->where(['is_public' => 1, 'content_category_id' => Post::CATEGORY_BLOG])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(6)
            ->all();

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col3',
            'title' => Yii::t('user','Вход'),
            'columnFirst' => [
                'test_block' => [
                    'view' => '@frontend/views/site/test',
                    'data' => [],
                ],
                'blog_column' => [
                    'view' => '@frontend/views/blocks/blog_block',
                    'data' => ['posts' => $blogPosts],
                ],
            ],
            'columnSecond' => [
                'login_block' => [
                    'view' => '@frontend/views/blocks/login_block',
                    'data' => compact('user'),
                ],
                'short_news' => [
                    'view' => '@frontend/views/blocks/news_block',
                    'data' => ['posts' => $newsPosts],
                ],
            ],
            'columnThird' => [
                'test_block' => [
                    'view' => '@frontend/views/site/test',
                    'data' => [],
                ],
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
        /** @var \amnah\yii2\user\models\User    $user */
        /** @var \amnah\yii2\user\models\Profile $profile */
        /** @var \amnah\yii2\user\models\Role    $role */

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

                $user->avatar = UploadedFile::getInstance($user, 'avatar');
                if(!empty($user->avatar))
                {
                    $asset = new Asset;
                    $asset->type = Asset::TYPE_AVATAR;
                    $asset->assetable_type = Asset::ASSETABLE_USER;
                    $asset->assetable_id = $user->id;
                    $asset->uploadedFile = $user->avatar;
                    $asset->cropData = $user->cropData;
                    $asset->saveCroppedAsset();
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

                Yii::$app->session->setFlash("Register-success", $successText);
            }

        }

        // backend render
        if( Yii::getAlias('@app') == Yii::getAlias('@backend')) {
            return $this->render('/backend/register', compact('user','profile'));
        }

        // frontend render
        $newsPosts = Post::find()
            ->where(['is_public' => 1, 'content_category_id' => Post::CATEGORY_NEWS])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(50)
            ->all();

        $blogPosts = Post::find()
            ->where(['is_public' => 1, 'content_category_id' => Post::CATEGORY_BLOG])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(6)
            ->all();

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col3',
            'title' => Yii::t('user','Вход'),
            'columnFirst' => [
                'test_block' => [
                    'view' => '@frontend/views/site/test',
                    'data' => [],
                ],
                'blog_column' => [
                    'view' => '@frontend/views/blocks/blog_block',
                    'data' => ['posts' => $blogPosts],
                ],
            ],
            'columnSecond' => [
                'register_block' => [
                    'view' => '@frontend/views/blocks/register_block',
                    'data' => compact('user','profile'),
                ],
                'short_news' => [
                    'view' => '@frontend/views/blocks/news_block',
                    'data' => ['posts' => $newsPosts],
                ],
            ],
            'columnThird' => [
                'test_block' => [
                    'view' => '@frontend/views/site/test',
                    'data' => [],
                ],
            ],
        ]);
    }

    /**
     * Process data after registration
     *
     * @param \amnah\yii2\user\models\User $user
     */
    protected function afterRegister($user)
    {
        /** @var \amnah\yii2\user\models\UserKey $userKey */

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
        /** @var \amnah\yii2\user\models\UserKey $userKey */
        /** @var \amnah\yii2\user\models\User $user */

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
        return $this->render("confirm", [
            "userKey" => $userKey,
            "success" => $success
        ]);
    }

    /**
     * Account
     */
    public function actionAccount()
    {
        /** @var \amnah\yii2\user\models\User $user */
        /** @var \amnah\yii2\user\models\UserKey $userKey */

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

            // save, set flash, and refresh page
            $user->save(false);
            $profile->save(false);
            Yii::$app->session->setFlash("Account-success", Yii::t("user", "Account updated"));
            return $this->refresh();
        }

        // render
        return $this->render("account", [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Profile
     */
    public function actionProfile()
    {
        /** @var \amnah\yii2\user\models\Profile $profile */

        // set up profile and load post data
        $profile = Yii::$app->user->identity->profile;

        // newsPosts
        $newsPosts = Post::find()
            ->where(['is_public' => 1, 'content_category_id' => Post::CATEGORY_NEWS])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(50)
            ->all();

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
                'pageSize' => 10,
            ],
        ]);

        // commentsDataProvider
        if(isset(Yii::$app->session['prev_login_time'])) {
            $loginTime = Yii::$app->session['prev_login_time'];
        } else {
            $loginTime = $profile->user->login_time;
        }
        Yii::$app->session['prev_login_time'] = $profile->user->login_time;
        $loginTime = "2012-12-05 22:01:11"; // test

        $sql = 'SELECT c1.id 
            FROM comments c1 
            WHERE c1.user_id=1 AND c1.id IN (
                SELECT c2.parent_id 
                FROM comments c2 
                WHERE c2.parent_id = c1.id AND c2.created_at > :time
            ) ORDER BY c1.id DESC';
        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);
        $command->bindValue(':time', $loginTime);
        $commentsQueryData = $command->queryAll();
        $commentsData = [];
        foreach ($commentsQueryData as $queryData) {
            $commentsData[] = $queryData['id'];
        }
        $allModels = [];
        foreach ($commentsData as $id) {
            $comments = \common\models\Comment::find()->where([
                'or', ['parent_id' => $id], ['id' => $id],
            ])->all();
            $sortedComments = [];
            foreach ($comments as $comment) 
            {
                $index = $comment->parent_id == null ? 0 : $comment->parent_id;
                $sortedComments[$index][] = $comment;
            }
            $allModels[] = $sortedComments;
        }
        // DataProvider
        $commentsDataProvider = new ArrayDataProvider([
            'allModels' => $allModels,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        // render
        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col2',
            'title' => Yii::t('user','Вход'),
            'columnFirst' => [
                'user_comments' => [
                    'view' => '@frontend/views/profile/user_comments',
                    'data' => compact('commentsDataProvider'),
                ],
                'profile' => [
                    'view' => '@frontend/views/profile/view',
                    'data' => compact('profile'),
                ],
                'blog_posts' => [
                    'view' => '@frontend/views/profile/blog_posts',
                    'data' => compact('blogPostsDataProvider'),
                ],
            ],
            'columnSecond' => [
                'test_block' => [
                    'view' => '@frontend/views/site/test',
                    'data' => [],
                ],
                'short_news' => [
                    'view' => '@frontend/views/blocks/news_block',
                    'data' => ['posts' => $newsPosts],
                ],
            ],
        ]);
    }

    /**
     * Resend email confirmation
     */
    public function actionResend()
    {
        /** @var \amnah\yii2\user\models\forms\ResendForm $model */

        // load post data and send email
        $model = Yii::$app->getModule("user")->model("ResendForm");
        if ($model->load(Yii::$app->request->post()) && $model->sendEmail()) {

            // set flash (which will show on the current page)
            Yii::$app->session->setFlash("Resend-success", Yii::t("user", "Confirmation email resent"));
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
        /** @var \amnah\yii2\user\models\User    $user */
        /** @var \amnah\yii2\user\models\UserKey $userKey */

        // find userKey of type email change
        $user    = Yii::$app->user->identity;
        $userKey = Yii::$app->getModule("user")->model("UserKey");
        $userKey = $userKey::findActiveByUser($user->id, $userKey::TYPE_EMAIL_CHANGE);
        if ($userKey) {

            // send email and set flash message
            $user->sendEmailConfirmation($userKey);
            Yii::$app->session->setFlash("Resend-success", Yii::t("user", "Confirmation email resent"));
        }

        // redirect to account page
        return $this->redirect(["/user/account"]);
    }

    /**
     * Cancel email change
     */
    public function actionCancel()
    {
        /** @var \amnah\yii2\user\models\User    $user */
        /** @var \amnah\yii2\user\models\UserKey $userKey */

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
            Yii::$app->session->setFlash("Cancel-success", Yii::t("user", "Email change cancelled"));
        }

        // go to account page
        return $this->redirect(["/user/account"]);
    }

    /**
     * Forgot password
     */
    public function actionForgot()
    {
        /** @var \amnah\yii2\user\models\forms\ForgotForm $model */

        // load post data and send email
        $model = Yii::$app->getModule("user")->model("ForgotForm");
        if ($model->load(Yii::$app->request->post()) && $model->sendForgotEmail()) {

            // set flash (which will show on the current page)
            Yii::$app->session->setFlash("Forgot-success", Yii::t("user", "Instructions to reset your password have been sent"));
        }

        // render
        if( Yii::getAlias('@app') == Yii::getAlias('@backend')) {
            return $this->render('forgot', compact('model'));
        }

        return $this->render('@frontend/views/site/index', [
            'templateType' => 'col3',
            'title' => Yii::t('user','Вход'),
            'columnFirst' => [
                'test_block' => [
                    'view' => '@frontend/views/site/test',
                    'data' => [],
                ],
            ],
            'columnSecond' => [
                'forgot_block' => [
                    'view' => '@frontend/views/blocks/forgot_block',
                    'data' => compact('model'),
                ],
                'test_block' => [
                    'view' => '@frontend/views/site/test',
                    'data' => [],
                ],
            ],
            'columnThird' => [
                'test_block' => [
                    'view' => '@frontend/views/site/test',
                    'data' => [],
                ],
            ],
        ]);
        return $this->render("forgot", [
            "model" => $model,
        ]);
    }

    /**
     * Reset password
     */
    public function actionReset($key)
    {
        /** @var \amnah\yii2\user\models\User    $user */
        /** @var \amnah\yii2\user\models\UserKey $userKey */

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
        return $this->render('reset', compact("user", "success"));
    }
}